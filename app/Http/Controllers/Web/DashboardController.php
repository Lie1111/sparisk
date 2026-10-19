<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PostureAssessment;
use App\Models\PostureIntervention;
use App\Models\PostureMeasurement;
use App\Services\Sparisk\SpariskInterpretationEngine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private SpariskInterpretationEngine $interpretation) {}
    /**
     * User-facing wording for the stored overall_status values, so the
     * dashboard reads the same as the assessment pages.
     */
    private const STATUS_META = [
        'GOOD' => ['label' => 'On track', 'tone' => 'emerald'],
        'MODERATE' => ['label' => 'Watch closely', 'tone' => 'amber'],
        'NEEDS IMPROVEMENT' => ['label' => 'Needs improvement', 'tone' => 'rose'],
    ];

    /**
     * Severity wording used by the stored patient_problem problems, worst first.
     */
    private const SEVERITY_TONES = [
        'severe' => 'rose',
        'moderate' => 'amber',
        'mild' => 'sky',
    ];

    public function __invoke(): Response
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfPreviousMonth = $now->copy()->subMonthNoOverflow()->startOfMonth();

        $assessmentsThisMonth = PostureAssessment::query()
            ->where('assessment_date', '>=', $startOfMonth->toDateString());

        $scoreThisMonth = (float) (clone $assessmentsThisMonth)->avg('overall_score');
        $scorePreviousMonth = (float) PostureAssessment::query()
            ->where('assessment_date', '>=', $startOfPreviousMonth->toDateString())
            ->where('assessment_date', '<', $startOfMonth->toDateString())
            ->avg('overall_score');

        $latestAssessments = $this->latestAssessmentPerPatient();

        return Inertia::render('dashboard', [
            'stats' => [
                'patients' => [
                    'total' => Patient::active()->count(),
                    'new_this_month' => Patient::active()->where('created_at', '>=', $startOfMonth)->count(),
                ],
                'assessments' => [
                    'total' => PostureAssessment::count(),
                    'this_month' => (clone $assessmentsThisMonth)->count(),
                    'average_score' => round((float) PostureAssessment::avg('overall_score'), 1),
                    'average_score_this_month' => round($scoreThisMonth, 1),
                    'score_delta' => $scorePreviousMonth > 0 ? round($scoreThisMonth - $scorePreviousMonth, 1) : null,
                ],
                'review' => [
                    'outstanding' => PostureAssessment::whereNotNull('review_status')->count(),
                    'measurements' => PostureMeasurement::count(),
                ],
                'library' => [
                    'interventions' => PostureIntervention::count(),
                ],
            ],
            'trend' => $this->monthlyTrend($now),
            'status_mix' => $this->statusMix(),
            'recent_assessments' => $this->recentAssessments(),
            'needs_attention' => $this->needsAttention($latestAssessments),
            'problem_areas' => $this->problemAreas(),
        ]);
    }

    /**
     * Web patient pages are linked by path, because the API resource registers
     * the same `patients.show` / `patients.index` route names.
     */
    private function patientUrl(int $patientId): string
    {
        return "/patients/{$patientId}";
    }

    /**
     * The newest assessment of every participant, used to decide who needs a
     * follow-up next.
     */
    private function latestAssessmentPerPatient(): Collection
    {
        $latestIds = PostureAssessment::query()
            ->selectRaw('max(id) as id')
            ->whereNotNull('patient_id')
            ->groupBy('patient_id')
            ->pluck('id');

        return PostureAssessment::with('patient:id,name,photo,age')
            ->whereIn('id', $latestIds)
            ->get();
    }

    /**
     * Assessment volume and average score for the last six months.
     */
    private function monthlyTrend(Carbon $now): array
    {
        $months = collect(range(5, 0))->map(fn (int $back) => $now->copy()->subMonthsNoOverflow($back)->startOfMonth());

        $grouped = PostureAssessment::query()
            ->where('assessment_date', '>=', $months->first()->toDateString())
            ->get(['assessment_date', 'overall_score'])
            ->filter(fn (PostureAssessment $assessment) => $assessment->assessment_date !== null)
            ->groupBy(fn (PostureAssessment $assessment) => $assessment->assessment_date->format('Y-m'));

        return $months->map(function (Carbon $month) use ($grouped) {
            $rows = $grouped->get($month->format('Y-m'), collect());

            return [
                'month' => $month->format('Y-m'),
                'label' => $month->format('M'),
                'assessments' => $rows->count(),
                'average_score' => $rows->isEmpty() ? null : round((float) $rows->avg('overall_score'), 1),
            ];
        })->all();
    }

    /**
     * How the stored overall_status values are distributed, including the
     * unclassified assessments that still need a review.
     */
    private function statusMix(): array
    {
        $total = PostureAssessment::count();
        $counts = PostureAssessment::query()
            ->selectRaw('overall_status, count(*) as total')
            ->groupBy('overall_status')
            ->pluck('total', 'overall_status');

        $mix = collect(self::STATUS_META)
            ->map(fn (array $meta, string $status) => [
                'key' => $status,
                'label' => $meta['label'],
                'tone' => $meta['tone'],
                'count' => (int) $counts->get($status, 0),
            ])
            ->values();

        $unclassified = (int) $counts->filter(fn ($count, $status) => $status === '' || $status === null)->sum()
            + (int) $counts->except(array_keys(self::STATUS_META))->sum();

        $mix->push([
            'key' => 'UNCLASSIFIED',
            'label' => 'Unclassified',
            'tone' => 'zinc',
            'count' => $unclassified,
        ]);

        return [
            'total' => $total,
            'buckets' => $mix->map(function (array $bucket) use ($total) {
                $bucket['percentage'] = $total > 0 ? round($bucket['count'] / $total * 100) : 0;

                return $bucket;
            })->all(),
        ];
    }

    /**
     * The most recent screenings, newest first.
     */
    private function recentAssessments(): array
    {
        return PostureAssessment::with('patient:id,name,photo')
            ->orderByDesc('assessment_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(fn (PostureAssessment $assessment) => [
                'id' => $assessment->id,
                'patient' => $assessment->patient ? [
                    'id' => $assessment->patient->id,
                    'name' => $assessment->patient->name,
                    'photo' => $assessment->patient->photo ? route('patients.photo', ['path' => $assessment->patient->photo]) : null,
                ] : null,
                'date' => $assessment->assessment_date?->toDateString(),
                'score' => $assessment->overall_score,
                'status' => $assessment->overall_status,
                'status_label' => self::STATUS_META[$assessment->overall_status]['label'] ?? 'Unclassified',
                'status_tone' => self::STATUS_META[$assessment->overall_status]['tone'] ?? 'zinc',
                'pattern' => $this->interpretation->patternTokenLabel($assessment->suspected_pattern)
                    ?: $this->interpretation->patternLabel($assessment->posture_classification),
                'needs_review' => $assessment->review_status !== null,
                'url' => $assessment->patient
                    ? $this->patientUrl($assessment->patient->id) . "/assessments/{$assessment->id}"
                    : route('assessments.show', ['postureAssessment' => $assessment->id]),
            ])
            ->all();
    }

    /**
     * Participants whose latest screening is not on track or still needs a
     * measurement review, lowest score first.
     */
    private function needsAttention(Collection $latestAssessments): array
    {
        return $latestAssessments
            ->filter(fn (PostureAssessment $assessment) => $assessment->patient !== null)
            ->filter(fn (PostureAssessment $assessment) => $assessment->overall_status !== 'GOOD' || $assessment->review_status !== null)
            ->sortBy(fn (PostureAssessment $assessment) => $assessment->overall_score ?? 0)
            ->take(5)
            ->map(function (PostureAssessment $assessment) {
                $reasons = [];

                if ($assessment->review_status !== null) {
                    $reasons[] = ['label' => 'Review', 'tone' => 'amber'];
                }

                if ($assessment->overall_status !== null && $assessment->overall_status !== 'GOOD') {
                    $reasons[] = [
                        'label' => self::STATUS_META[$assessment->overall_status]['label'] ?? $assessment->overall_status,
                        'tone' => self::STATUS_META[$assessment->overall_status]['tone'] ?? 'zinc',
                    ];
                }

                return [
                    'patient' => [
                        'id' => $assessment->patient->id,
                        'name' => $assessment->patient->name,
                        'photo' => $assessment->patient->photo ? route('patients.photo', ['path' => $assessment->patient->photo]) : null,
                        'age' => $assessment->patient->age,
                    ],
                    'score' => $assessment->overall_score,
                    'date' => $assessment->assessment_date?->toDateString(),
                    'reasons' => $reasons,
                    'url' => $this->patientUrl($assessment->patient->id),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * The areas that keep showing up in the stored patient_problem findings,
     * ranked by how often they were flagged.
     */
    private function problemAreas(): array
    {
        $areas = [];

        PostureAssessment::query()
            ->whereNotNull('patient_problem')
            ->orderByDesc('id')
            ->limit(50)
            ->pluck('patient_problem')
            ->each(function ($problem) use (&$areas) {
                foreach (($problem['problems'] ?? []) as $finding) {
                    $area = $finding['area'] ?? null;

                    if (!$area) {
                        continue;
                    }

                    $severity = $finding['severity'] ?? null;

                    $areas[$area] ??= ['area' => $area, 'count' => 0, 'severities' => []];
                    $areas[$area]['count']++;

                    if ($severity) {
                        $areas[$area]['severities'][$severity] = ($areas[$area]['severities'][$severity] ?? 0) + 1;
                    }
                }
            });

        $ranked = collect($areas)
            ->map(function (array $area) {
                arsort($area['severities']);
                $worst = array_key_first($area['severities']);

                return [
                    'area' => $area['area'],
                    'count' => $area['count'],
                    'severity' => $worst,
                    'tone' => self::SEVERITY_TONES[$worst] ?? 'zinc',
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->take(6)
            ->all();

        $highest = collect($ranked)->max('count') ?: 1;

        return collect($ranked)
            ->map(fn (array $area) => $area + ['percentage' => (int) round($area['count'] / $highest * 100)])
            ->all();
    }
}
