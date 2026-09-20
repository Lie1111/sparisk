<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SATA BMI reference bands, keyed by SATA age group + exact age + gender.
 *
 * The table is the editable source of truth: SATA can amend the official
 * values at any time and the calculation engine picks them up automatically,
 * because the engine only performs a band lookup and never hard-codes a
 * threshold. When the table is empty (fresh install, tests) the locked
 * config/sparisk.php definitions are used instead.
 */
class BmiReference extends Model
{
    protected $fillable = [
        'age_group', 'age_years', 'gender',
        'min_bmi', 'max_bmi', 'category', 'source', 'is_verified',
    ];

    protected $casts = [
        'age_years' => 'integer',
        'min_bmi' => 'decimal:2',
        'max_bmi' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    /**
     * Ordered bands for one lookup key. Children pass an exact age + gender;
     * adults pass neither because the adult cut-offs are shared.
     *
     * @return array<int, array{min: float, max: float|null, category: string, source: string, is_verified: bool}>
     */
    public static function rowsFor(string $ageGroup, ?int $ageYears = null, ?string $gender = null): array
    {
        $rows = self::query()
            ->where('age_group', $ageGroup)
            ->when($ageYears === null, fn ($q) => $q->whereNull('age_years'), fn ($q) => $q->where('age_years', $ageYears))
            ->when($gender === null, fn ($q) => $q->whereNull('gender'), fn ($q) => $q->where('gender', $gender))
            ->orderBy('min_bmi')
            ->get();

        if ($rows->isEmpty()) {
            return self::configRows($ageYears, $gender);
        }

        return $rows->map(fn (self $row) => [
            'min' => (float) $row->min_bmi,
            'max' => $row->max_bmi === null ? null : (float) $row->max_bmi,
            'category' => $row->category,
            'source' => $row->source,
            'is_verified' => (bool) $row->is_verified,
        ])->all();
    }

    /**
     * Bands expanded from config/sparisk.php, used when the table is empty.
     *
     * @return array<int, array{min: float, max: float|null, category: string, source: string, is_verified: bool}>
     */
    public static function configRows(?int $ageYears, ?string $gender): array
    {
        if ($ageYears === null || $gender === null) {
            $config = config('sparisk.bmi_references');

            return collect($config['adult'])->map(fn (array $band) => [
                'min' => (float) $band['min'],
                'max' => $band['max'] === null ? null : (float) $band['max'],
                'category' => $band['category'],
                'source' => $config['adult_source'],
                'is_verified' => false,
            ])->all();
        }

        return self::expandChildAge($ageYears, $gender);
    }

    /**
     * Expand one exact age + gender of the child dataset into contiguous bands.
     *
     * @return array<int, array{min: float, max: float|null, category: string, source: string, is_verified: bool}>
     */
    public static function expandChildAge(int $ageYears, string $gender): array
    {
        $config = config('sparisk.bmi_references');
        $cutoffs = $config['child'][$gender][$ageYears] ?? null;

        if ($cutoffs === null) {
            return [];
        }

        return collect($config['child_categories'])->map(fn (array $band) => [
            'min' => $band['from'] === null ? 0.0 : (float) $cutoffs[$band['from']],
            'max' => $band['to'] === null ? null : (float) $cutoffs[$band['to']],
            'category' => $band['category'],
            'source' => $config['child_source'],
            'is_verified' => false,
        ])->all();
    }

    /**
     * Every band the config defines, ready to be written by the seeder, so the
     * table can never drift from the locked reference block.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function seedRows(): array
    {
        $config = config('sparisk.bmi_references');
        $rows = [];

        foreach ($config['child_age_groups'] as $ageGroup) {
            $group = config("sparisk.age_groups.{$ageGroup}");

            for ($age = $group['min']; $age <= $group['max']; $age++) {
                foreach (['male', 'female'] as $gender) {
                    foreach (self::expandChildAge($age, $gender) as $band) {
                        $rows[] = [
                            'age_group' => $ageGroup,
                            'age_years' => $age,
                            'gender' => $gender,
                            'min_bmi' => $band['min'],
                            'max_bmi' => $band['max'],
                            'category' => $band['category'],
                            'source' => $band['source'],
                            'is_verified' => false,
                        ];
                    }
                }
            }
        }

        // Adult cut-offs are shared, but each SATA adult age group gets its own
        // rows so SATA can later tailor 50+ without a code change.
        foreach (array_diff(array_keys(config('sparisk.age_groups')), $config['child_age_groups']) as $ageGroup) {
            foreach (self::configRows(null, null) as $band) {
                $rows[] = [
                    'age_group' => $ageGroup,
                    'age_years' => null,
                    'gender' => null,
                    'min_bmi' => $band['min'],
                    'max_bmi' => $band['max'],
                    'category' => $band['category'],
                    'source' => $band['source'],
                    'is_verified' => false,
                ];
            }
        }

        return $rows;
    }
}
