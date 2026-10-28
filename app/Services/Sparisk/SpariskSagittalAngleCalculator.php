<?php

namespace App\Services\Sparisk;

/**
 * The landmark geometry behind the sagittal side-view measurements.
 *
 * This mirrors the definitions the mobile app implements
 * (`PostureMeasurementCalculator`) so a stored assessment can be rebuilt from
 * its saved landmarks. The shared contract is
 * `config('sparisk.measurement_definitions')`: both implementations must use
 * the same axis, landmarks and anterior-positive sign.
 *
 * It is intentionally small — only the measurements that need re-deriving when
 * a formula version changes — and is not used on the normal capture path.
 */
class SpariskSagittalAngleCalculator
{
    private const CVA_NEUTRAL_SHOULDER_ANGLE = 86.0;

    private const CVA_NEUTRAL_VALUE = 50.0;

    private const SAGITTAL_CURVE_NEUTRAL = 38.0;

    /**
     * +1 when the front of the body points toward the image right, -1 when it
     * points left. The toe always sits in front of the heel.
     */
    public function facing(array $landmarks, string $side): float
    {
        [$toeIndex, $heelIndex] = $side === 'right_side' ? [32, 30] : [31, 29];

        $toe = $landmarks[$toeIndex] ?? null;
        $heel = $landmarks[$heelIndex] ?? null;

        if ($toe === null || $heel === null) {
            return $side === 'right_side' ? 1.0 : -1.0;
        }

        $dx = ((float) $toe['x']) - ((float) $heel['x']);

        if (abs($dx) < 0.0001) {
            return $side === 'right_side' ? 1.0 : -1.0;
        }

        return $dx > 0 ? 1.0 : -1.0;
    }

    /**
     * Anterior-positive plumb deviation between two landmarks.
     */
    public function sagittalDeviation(array $top, array $bottom, float $facing, float $aspect = 1.0): float
    {
        $dx = ($top['x'] * $aspect) - ($bottom['x'] * $aspect);
        $dy = $bottom['y'] - $top['y'];

        return round($this->foldOrientation(atan2($dx, $dy) * 180 / M_PI) * $facing, 2);
    }

    /**
     * Craniovertebral angle on the SATA scale.
     */
    public function cva(array $ear, array $shoulder, float $aspect = 1.0): float
    {
        $up = $shoulder['y'] - $ear['y'];
        $forward = abs(($ear['x'] * $aspect) - ($shoulder['x'] * $aspect));

        if ($up <= 0.001) {
            return 0.0;
        }

        $shoulderAngle = max(0.0, min(90.0, atan2($up, $forward) * 180 / M_PI));

        return round(max(0.0, min(90.0, self::CVA_NEUTRAL_VALUE + ($shoulderAngle - self::CVA_NEUTRAL_SHOULDER_ANGLE))), 2);
    }

    /**
     * Upper-back / lower-back curve indicator on the SATA scale.
     */
    public function curve(array $top, array $vertex, array $bottom, float $aspect = 1.0): float
    {
        $angle = $this->interiorAngle($top, $vertex, $bottom, $aspect);
        $bend = max(0.0, min(180.0, 180.0 - $angle));

        return round(self::SAGITTAL_CURVE_NEUTRAL + $bend, 2);
    }

    /**
     * Exterior foot progression against the forward axis (external positive).
     */
    public function footProgression(array $ankle, array $toe, float $midlineX, float $aspect = 1.0): float
    {
        $dx = ($toe['x'] * $aspect) - ($ankle['x'] * $aspect);
        $dy = $toe['y'] - $ankle['y'];

        if ($dy <= 0.001) {
            return 0.0;
        }

        $magnitude = atan2(abs($dx), $dy) * 180 / M_PI;
        $outward = abs(($toe['x'] * $aspect) - $midlineX) >= abs(($ankle['x'] * $aspect) - $midlineX);

        return round($outward ? $magnitude : -$magnitude, 2);
    }

    private function interiorAngle(array $p1, array $p2, array $p3, float $aspect): float
    {
        $v1x = ($p1['x'] - $p2['x']) * $aspect;
        $v1y = $p1['y'] - $p2['y'];
        $v2x = ($p3['x'] - $p2['x']) * $aspect;
        $v2y = $p3['y'] - $p2['y'];

        $dot = $v1x * $v2x + $v1y * $v2y;
        $mag1 = sqrt($v1x ** 2 + $v1y ** 2);
        $mag2 = sqrt($v2x ** 2 + $v2y ** 2);

        if ($mag1 < 0.000001 || $mag2 < 0.000001) {
            return 0.0;
        }

        return acos(max(-1.0, min(1.0, $dot / ($mag1 * $mag2)))) * 180 / M_PI;
    }

    private function foldOrientation(float $angle): float
    {
        $folded = fmod($angle, 180.0);

        if ($folded > 90.0) {
            $folded -= 180.0;
        }

        if ($folded <= -90.0) {
            $folded += 180.0;
        }

        return $folded;
    }
}
