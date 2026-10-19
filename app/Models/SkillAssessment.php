<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One sitting of the SATA Aquatic Skills Assessment checklist.
 *
 * Answers are stored as item number => bool and scored out of one point per
 * item, so the ceiling is simply how many items were asked.
 */
class SkillAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'answers', 'total_score', 'max_score',
        'category_scores', 'assessed_on', 'notes',
    ];

    protected $casts = [
        'answers' => 'array',
        'category_scores' => 'array',
        'assessed_on' => 'date',
        'total_score' => 'integer',
        'max_score' => 'integer',
    ];

    protected $appends = ['percentage'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The checklist definition this model scores against.
     */
    public static function catalogue(): array
    {
        return config('sparisk.aquatic_skills');
    }

    /**
     * Score a set of answers against the catalogue.
     *
     * Anything other than an explicit yes is a no: the paper form has only two
     * boxes, so a missing answer must never be counted as an achievement.
     *
     * @param  array<int|string, mixed>  $answers  item number => answered yes
     * @return array{answers: array<int, bool>, total_score: int, max_score: int, category_scores: array<int, array<string, mixed>>}
     */
    public static function score(array $answers): array
    {
        $catalogue = self::catalogue();
        $items = $catalogue['items'] ?? [];
        $categories = $catalogue['categories'] ?? [];

        $normalised = [];
        $achieved = [];
        $totals = [];

        foreach ($items as $number => $item) {
            $yes = filter_var($answers[$number] ?? $answers[(string) $number] ?? false, FILTER_VALIDATE_BOOLEAN);
            $normalised[$number] = $yes;

            $category = $item['category'];
            $totals[$category] = ($totals[$category] ?? 0) + 1;
            if ($yes) {
                $achieved[$category] = ($achieved[$category] ?? 0) + 1;
            }
        }

        $categoryScores = [];
        foreach ($categories as $key => $label) {
            $categoryScores[] = [
                'key' => $key,
                'label' => $label,
                'achieved' => $achieved[$key] ?? 0,
                'total' => $totals[$key] ?? 0,
            ];
        }

        return [
            'answers' => $normalised,
            'total_score' => array_sum(array_map(fn ($yes) => $yes ? 1 : 0, $normalised)),
            'max_score' => count($normalised),
            'category_scores' => $categoryScores,
        ];
    }

    /**
     * The score as a percentage of the items asked.
     *
     * Guarded against a zero ceiling so an empty checklist reads 0% rather than
     * throwing.
     */
    public function getPercentageAttribute(): float
    {
        if ($this->max_score <= 0) {
            return 0.0;
        }

        return round($this->total_score / $this->max_score * 100, 1);
    }

    /**
     * Per-category percentages, which is how the client asked for the category
     * chart: the four categories hold different numbers of items, so raw counts
     * would always favour the longest one.
     *
     * @return array<int, array<string, mixed>>
     */
    public function categoryPercentages(): array
    {
        $scores = $this->category_scores ?? [];

        // Fall back to the current catalogue for rows written before the
        // frozen breakdown existed.
        if ($scores === []) {
            $scored = self::score($this->answers ?? []);
            $scores = $scored['category_scores'];
        }

        return array_map(function (array $category) {
            $total = (int) ($category['total'] ?? 0);
            $achieved = (int) ($category['achieved'] ?? 0);

            return $category + [
                'percentage' => $total > 0 ? round($achieved / $total * 100, 1) : 0.0,
            ];
        }, $scores);
    }
}
