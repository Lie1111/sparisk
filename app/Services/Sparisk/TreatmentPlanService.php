<?php

namespace App\Services\Sparisk;

use App\Models\MuscleCondition;
use App\Models\PostureAssessment;
use App\Models\PostureIntervention;
use Illuminate\Support\Collection;

/**
 * The patient's one treatment plan for an assessment. The admin authors
 * aquatic exercises, a massage therapy plan and general exercises per
 * condition; this merges the plans of the conditions the assessment found
 * into a single plan, each item keeping the weekdays it runs on. The app
 * builds the weekly program from those days, so what the admin sets up is
 * exactly what the patient sees.
 */
class TreatmentPlanService
{
    /** The plan shown when the assessment matches no condition. */
    public const GENERAL_SLUG = 'normal_neutral';
    public const GENERAL_NAME = 'General Posture Care';

    /** Age band the catalogue is authored for first. */
    private const FALLBACK_AGE_GROUP = '13-18';

    /**
     * Most items per program in the merged plan, so a patient with several
     * conditions gets a week they can actually follow.
     */
    private const PROGRAM_LIMITS = [
        'aquatic_exercise' => 4,
        'massage_therapy' => 3,
        'general_exercise' => 3,
    ];

    public function __construct(
        private MuscleMapService $muscleMap,
        private SpariskMeasurementEngine $measurements,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function forAssessment(PostureAssessment $assessment): array
    {
        $ageGroup = $this->measurements->resolveAgeGroup($assessment->patient?->age);

        $conditions = $this->planConditions($assessment);

        return [
            'age_group' => $ageGroup,
            'age_group_label' => config("sparisk.age_groups.{$ageGroup}.label", $ageGroup),
            'programs' => config('sparisk.intervention_programs'),
            'weekdays' => PostureIntervention::WEEKDAYS,
            'conditions' => $conditions,
            'items' => $this->merge($conditions, $ageGroup),
        ];
    }

    /**
     * The problems the plan treats: what the assessment confirmed or
     * measured. A suspected pattern is only used when nothing firmer was
     * found, and the general plan when nothing was found at all.
     *
     * @return array<int, array{slug: string, name: string, certainty: string}>
     */
    private function planConditions(PostureAssessment $assessment): array
    {
        $matches = collect($this->muscleMap->detect($assessment))->map(fn(array $m) => [
            'slug' => $m['condition']->slug,
            'name' => $m['condition']->name,
            'certainty' => $m['certainty'],
        ]);

        $firm = $matches->whereIn('certainty', ['confirmed', 'measured'])->values();
        $chosen = $firm->isNotEmpty() ? $firm : $matches;

        return $chosen->isNotEmpty()
            ? $chosen->all()
            : [['slug' => self::GENERAL_SLUG, 'name' => self::GENERAL_NAME, 'certainty' => 'general']];
    }

    /**
     * One plan from every condition's items: per program, take items in turn
     * from each condition (main condition first), skip repeated titles, and
     * stop at the program's limit.
     *
     * @param  array<int, array{slug: string, name: string}>  $conditions
     * @return array<int, array<string, mixed>>
     */
    private function merge(array $conditions, string $ageGroup): array
    {
        $byCondition = array_map(fn(array $c) => $this->items($c['slug'], $ageGroup)
            ->map(fn(PostureIntervention $i) => $this->serialize($i) + ['condition' => $c['name']])
            ->groupBy('program'), $conditions);

        $plan = [];

        foreach (self::PROGRAM_LIMITS as $program => $limit) {
            $queues = array_map(fn($groups) => array_values(($groups[$program] ?? collect())->all()), $byCondition);
            $titles = [];
            $taken = 0;

            for ($round = 0; $taken < $limit && $queues !== []; $round++) {
                $progress = false;
                foreach ($queues as $queue) {
                    $item = $queue[$round] ?? null;
                    if ($item === null) {
                        continue;
                    }
                    $progress = true;
                    $key = $this->titleKey($item['title']);
                    if (isset($titles[$key]) || $taken >= $limit) {
                        continue;
                    }
                    $titles[$key] = true;
                    $plan[] = $item;
                    $taken++;
                }
                if (!$progress) {
                    break;
                }
            }
        }

        return $plan;
    }

    /** "Hamstrings" and "Hamstring (left)" count as the same item as "Hamstring". */
    private function titleKey(string $title): string
    {
        $words = preg_split('/[^a-z0-9]+/', strtolower(preg_replace('/\(.*?\)/', '', $title)), -1, PREG_SPLIT_NO_EMPTY);

        return implode(' ', array_map(fn($w) => strlen($w) > 3 ? rtrim($w, 's') : $w, $words));
    }

    /**
     * The admin's items for one condition, in the admin's order. The
     * adolescent band is the authored default, so another band without its
     * own items falls back to it per program.
     *
     * @return Collection<int, PostureIntervention>
     */
    public function items(string $slug, string $ageGroup): Collection
    {
        $rows = PostureIntervention::query()
            ->where('posture_type', $slug)
            ->whereIn('age_group', array_unique([$ageGroup, self::FALLBACK_AGE_GROUP]))
            ->orderBy('order_index')
            ->orderBy('id')
            ->get();

        return $rows->groupBy('program')->flatMap(function (Collection $program) use ($ageGroup) {
            $own = $program->where('age_group', $ageGroup);

            return $own->isNotEmpty() ? $own : $program->where('age_group', self::FALLBACK_AGE_GROUP);
        })->values();
    }

    /**
     * Posture types the admin can author plans for: every muscle-map
     * condition plus the general plan.
     *
     * @return array<string, string>
     */
    public static function postureTypes(): array
    {
        return MuscleCondition::orderBy('order_index')->orderBy('id')->pluck('name', 'slug')->all()
            + [self::GENERAL_SLUG => self::GENERAL_NAME];
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(PostureIntervention $i): array
    {
        return [
            'id' => $i->id,
            'program' => $i->program,
            'category' => $i->category,
            'level' => $i->level,
            'title' => $i->title,
            'description' => $i->description,
            'sets_reps' => $i->sets_reps,
            'frequency' => $i->frequency,
            'duration_minutes' => $i->duration_minutes,
            'days' => $i->scheduledDays(),
            'image' => $i->image_src,
        ];
    }
}
