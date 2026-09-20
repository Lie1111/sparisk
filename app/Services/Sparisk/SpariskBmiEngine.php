<?php

namespace App\Services\Sparisk;

use App\Models\BmiReference;
use App\Models\Patient;

/**
 * Central SATA BMI engine.
 *
 * BMI = Weight (kg) / Height (m)². Clients always send height in centimetres;
 * the conversion happens here so the mobile app, the web pages and the API can
 * never disagree — every surface displays exactly what this engine returns.
 *
 * Flow: validate input → calculate BMI → resolve SATA age group → look up the
 * age + gender reference bands → determine the category.
 *
 * The thresholds are never hard-coded: they come from the editable
 * `bmi_references` table (config/sparisk.php is only a fallback), so SATA can
 * update the clinical reference without a code change.
 */
class SpariskBmiEngine
{
    /** BMI is displayed to one decimal place, the documented SATA format. */
    public const PRECISION = 1;

    /** Absolute bounds accepted for the raw inputs. */
    private const MAX_AGE = 150;
    private const MAX_HEIGHT_CM = 300;
    private const MAX_WEIGHT_KG = 500;

    public function __construct(private SpariskMeasurementEngine $measurementEngine) {}

    /**
     * Validate, calculate and classify in one call.
     *
     * The returned payload always has the same shape; `valid` tells the caller
     * whether the input could be classified and `errors` explains what is
     * missing. Classification is intentionally performed on the rounded value
     * so the displayed BMI always agrees with the displayed category.
     */
    public function calculate(mixed $age, mixed $gender, mixed $heightCm, mixed $weightKg): array
    {
        $age = $this->toInt($age);
        $gender = $this->normaliseGender($gender);
        $height = $this->toFloat($heightCm);
        $weight = $this->toFloat($weightKg);

        $errors = $this->validate($age, $gender, $height, $weight);

        if ($errors !== []) {
            return $this->emptyPayload($errors);
        }

        $heightMeters = $height / 100;
        $bmi = round($weight / ($heightMeters * $heightMeters), self::PRECISION);

        return $this->classify($bmi, $age, $gender) + [
            'valid' => true,
            'errors' => (object) [],
            'bmi' => $bmi,
            'bmi_display' => $this->format($bmi).' kg/m²',
            'height_m' => round($heightMeters, 2),
        ];
    }

    /**
     * Same as calculate(), but skipped entirely for a patient without the
     * anthropometric data needed to produce a meaningful BMI.
     */
    public function forPatient(?Patient $patient): ?array
    {
        if ($patient === null) {
            return null;
        }

        if ($patient->age === null && $patient->height === null && $patient->weight === null) {
            return null;
        }

        return $this->calculate($patient->age, $patient->gender, $patient->height, $patient->weight);
    }

    /**
     * SATA validation rules for the BMI inputs (age, gender, height, weight).
     *
     * @return array<string, string> field => message
     */
    public function validate(?int $age, ?string $gender, ?float $height, ?float $weight): array
    {
        $minAge = $this->minimumAge();
        $errors = [];

        if ($age === null || $age < $minAge || $age > self::MAX_AGE) {
            $errors['age'] = "Age must be between {$minAge} and ".self::MAX_AGE.' years.';
        }

        if ($height === null || $height <= 0) {
            $errors['height'] = 'Height must be greater than 0 cm.';
        } elseif ($height > self::MAX_HEIGHT_CM) {
            $errors['height'] = 'Height must not exceed '.self::MAX_HEIGHT_CM.' cm.';
        }

        if ($weight === null || $weight <= 0) {
            $errors['weight'] = 'Weight must be greater than 0 kg.';
        } elseif ($weight > self::MAX_WEIGHT_KG) {
            $errors['weight'] = 'Weight must not exceed '.self::MAX_WEIGHT_KG.' kg.';
        }

        // Adults share one cut-off for both genders, so gender is only
        // mandatory where it actually changes the reference (age 6–18).
        if ($this->isChildAge($age) && $gender === null) {
            $errors['gender'] = 'Gender is required to classify BMI for children aged 6–18.';
        }

        return $errors;
    }

    /**
     * Resolve the SATA age group and the reference band for an already
     * calculated BMI. Children look up exact age + gender; adults use the
     * shared adult classification.
     */
    public function classify(float $bmi, ?int $age, ?string $gender): array
    {
        $ageGroup = $this->measurementEngine->resolveAgeGroup($age);
        $isChild = $this->isChildAge($age);

        $rows = BmiReference::rowsFor($ageGroup, $isChild ? $age : null, $isChild ? $gender : null);
        $band = $this->matchBand($rows, $bmi);

        return [
            'category' => $band['category'] ?? null,
            'age_group' => $ageGroup,
            'age_group_label' => config("sparisk.age_groups.{$ageGroup}.label"),
            'reference' => $band === null ? null : ['min' => $band['min'], 'max' => $band['max']],
            'reference_label' => $this->referenceLabel($band),
            'source' => $band['source'] ?? null,
            'is_verified' => (bool) ($band['is_verified'] ?? false),
        ];
    }

    /**
     * The lowest age SATA can classify, taken from the age group definition so
     * it stays in step with config/sparisk.php.
     */
    public function minimumAge(): int
    {
        return (int) min(array_column(config('sparisk.age_groups'), 'min'));
    }

    public function isChildAge(?int $age): bool
    {
        if ($age === null) {
            return false;
        }

        return in_array(
            $this->measurementEngine->resolveAgeGroup($age),
            config('sparisk.bmi_references.child_age_groups'),
            true
        );
    }

    /**
     * First band whose [min, max) window contains the BMI; `max` is exclusive.
     */
    private function matchBand(array $rows, float $bmi): ?array
    {
        foreach ($rows as $row) {
            if ($bmi >= $row['min'] && ($row['max'] === null || $bmi < $row['max'])) {
                return $row;
            }
        }

        return null;
    }

    private function referenceLabel(?array $band): ?string
    {
        if ($band === null) {
            return null;
        }

        if ($band['max'] === null) {
            return '≥ '.$this->format($band['min']).' kg/m²';
        }

        return $this->format($band['min']).' – '.$this->format($band['max']).' kg/m²';
    }

    private function emptyPayload(array $errors): array
    {
        return [
            'valid' => false,
            'errors' => (object) $errors,
            'bmi' => null,
            'bmi_display' => null,
            'height_m' => null,
            'category' => null,
            'age_group' => null,
            'age_group_label' => null,
            'reference' => null,
            'reference_label' => null,
            'source' => null,
            'is_verified' => false,
        ];
    }

    private function format(float $value): string
    {
        return number_format($value, self::PRECISION);
    }

    private function toInt(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function toFloat(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }

    private function normaliseGender(mixed $gender): ?string
    {
        $gender = is_string($gender) ? strtolower(trim($gender)) : null;

        return in_array($gender, ['male', 'female'], true) ? $gender : null;
    }
}
