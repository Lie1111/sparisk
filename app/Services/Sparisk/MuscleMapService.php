<?php

namespace App\Services\Sparisk;

use App\Models\MuscleBodyImage;
use App\Models\MuscleCondition;
use App\Models\PostureAssessment;
use App\Models\PostureMeasurement;
use Illuminate\Support\Collection;

/**
 * Works out which muscle-map conditions an assessment shows and builds the
 * payload the mobile app draws: the muscle body images, the status legend and,
 * per condition, the patient wording and the weak / tight highlights.
 */
class MuscleMapService
{
    /** Prefixes the decision engine puts in front of a suspected pattern key. */
    private const TOKEN_PREFIXES = ['MILD_', 'MODERATE_', 'SEVERE_', 'RIGHT_', 'LEFT_'];

    /**
     * The conditions an assessment matches, most certain first.
     *
     * A confirmed classification gives `confirmed`; a measurement condition
     * gives `measured`; the suspected pattern of an unclassified posture gives
     * `possible`, so the app never states a finding the engine did not confirm.
     *
     * @return array<int, array{condition: MuscleCondition, certainty: string, evidence: array<int, string>}>
     */
    public function detect(PostureAssessment $assessment): array
    {
        $confirmed = $assessment->classifications()
            ->pluck('classification_name')
            ->map(fn($name) => $this->classificationKey((string) $name))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $possible = collect([$assessment->suspected_pattern, $assessment->secondary_pattern])
            ->filter()
            ->map(fn($token) => $this->tokenKey((string) $token))
            ->reject(fn($key) => in_array($key, $confirmed, true))
            ->unique()
            ->values()
            ->all();

        $measurements = $assessment->measurements()->get()->keyBy('section');
        $certaintyRank = ['confirmed' => 0, 'measured' => 1, 'possible' => 2];

        $matches = [];

        foreach (MuscleCondition::where('is_active', true)->orderBy('order_index')->get() as $condition) {
            $detection = $condition->detection ?? [];
            $keys = array_map(fn($key) => $this->classificationKey((string) $key), $detection['classifications'] ?? []);

            $certainty = null;
            $evidence = [];

            if ($hits = array_values(array_intersect($keys, $confirmed))) {
                $certainty = 'confirmed';
                $evidence = array_map(fn($key) => 'Classified as ' . $this->humanKey($key), $hits);
            }

            foreach ($detection['sections'] ?? [] as $rule) {
                $measurement = $measurements->get($rule['section'] ?? '');
                if ($this->sectionMatches($rule, $measurement)) {
                    $certainty ??= 'measured';
                    $evidence[] = trim(($measurement->label ?: $measurement->section) . ' — ' . ($measurement->status_text ?: ucfirst((string) $measurement->alignment_status)));
                }
            }

            if ($certainty === null && ($hits = array_values(array_intersect($keys, $possible)))) {
                $certainty = 'possible';
                $evidence = array_map(fn($key) => 'Suspected ' . $this->humanKey($key) . ' pattern', $hits);
            }

            if ($certainty !== null) {
                $matches[] = ['condition' => $condition, 'certainty' => $certainty, 'evidence' => array_values(array_unique($evidence))];
            }
        }

        usort($matches, fn($a, $b) => $certaintyRank[$a['certainty']] <=> $certaintyRank[$b['certainty']]);

        return $matches;
    }

    /**
     * The muscle map for one assessment, drawn on the patient's gender.
     *
     * @return array<string, mixed>
     */
    public function forAssessment(PostureAssessment $assessment): array
    {
        $gender = $this->gender($assessment->patient?->gender);

        $conditions = array_map(
            fn(array $match) => array_merge($this->serializeCondition($match['condition'], $gender), [
                'certainty' => $match['certainty'],
                'evidence' => $match['evidence'],
            ]),
            $this->detect($assessment),
        );

        return array_merge($this->legend($gender), ['conditions' => $conditions]);
    }

    /**
     * Images, views and statuses shared by every muscle map payload.
     *
     * @return array<string, mixed>
     */
    public function legend(string $gender): array
    {
        return [
            'gender' => $gender,
            'views' => config('sparisk.muscle_views'),
            'statuses' => config('sparisk.muscle_statuses'),
            // Relative to the site root; the app prefixes its own API domain.
            'body_images' => array_map(
                fn(string $path) => 'storage/' . $path,
                MuscleBodyImage::pathsByGender()[$gender] ?? [],
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function serializeCondition(MuscleCondition $condition, string $gender): array
    {
        $highlights = $condition->highlights->where('gender', $gender)->values();

        return [
            'id' => $condition->id,
            'slug' => $condition->slug,
            'name' => $condition->name,
            'description' => $condition->description,
            'effects' => $condition->effectList(),
            'muscles' => $this->muscleSummary($highlights),
            'highlights' => $highlights->groupBy('view')->map(fn(Collection $items) => $items->map(fn($h) => [
                'id' => $h->id,
                'muscle' => $h->muscle,
                'status' => $h->status,
                'x' => $h->x,
                'y' => $h->y,
                'width' => $h->width,
                'height' => $h->height,
                'rotation' => $h->rotation,
                'points' => $h->points,
            ])->values())->all(),
        ];
    }

    public function gender(?string $gender): string
    {
        return strtolower((string) $gender) === 'female' ? 'female' : 'male';
    }

    /**
     * One row per muscle + status (left and right shapes collapse), worst
     * status first so the patient reads what to treat first.
     *
     * @return array<int, array{muscle: string, status: string, views: array<int, string>}>
     */
    private function muscleSummary(Collection $highlights): array
    {
        $rank = ['severe' => 0, 'tight' => 1, 'weak' => 2];
        $views = array_keys(config('sparisk.muscle_views'));

        return $highlights
            ->groupBy(fn($h) => $h->status . '|' . $h->muscle)
            ->map(fn(Collection $items) => [
                'muscle' => $items->first()->muscle,
                'status' => $items->first()->status,
                'views' => $items->pluck('view')->unique()->sortBy(fn($v) => array_search($v, $views, true))->values()->all(),
            ])
            ->sortBy(fn($row) => sprintf('%d-%s', $rank[$row['status']] ?? 9, $row['muscle']))
            ->values()
            ->all();
    }

    private function sectionMatches(array $rule, ?PostureMeasurement $measurement): bool
    {
        if ($measurement === null || $measurement->review_required) {
            return false;
        }

        if (!in_array($measurement->alignment_status, $rule['level'] ?? [], true)) {
            return false;
        }

        $direction = $rule['direction'] ?? null;

        return $direction === null || $measurement->deviation_direction === $direction;
    }

    /** "Kyphosis-Lordosis" / "Flat Back" -> "KYPHOSIS_LORDOSIS" / "FLAT_BACK". */
    private function classificationKey(string $name): string
    {
        return trim(preg_replace('/[^A-Z0-9]+/', '_', strtoupper(trim($name))), '_');
    }

    /** "MODERATE_FLAT_BACK" / "RIGHT_KYPHOSIS" -> "FLAT_BACK" / "KYPHOSIS". */
    private function tokenKey(string $token): string
    {
        $key = $this->classificationKey($token);

        foreach (self::TOKEN_PREFIXES as $prefix) {
            if (str_starts_with($key, $prefix)) {
                return substr($key, strlen($prefix));
            }
        }

        return $key;
    }

    private function humanKey(string $key): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $key)));
    }
}
