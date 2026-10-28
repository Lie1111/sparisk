<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The muscle anatomy body image uploaded for one gender + view.
 */
class MuscleBodyImage extends Model
{
    protected $fillable = ['gender', 'view', 'path'];

    /**
     * Public-disk path per gender and view, falling back to the default
     * character image until an admin uploads a muscle anatomy image.
     *
     * @return array<string, array<string, string>>
     */
    public static function pathsByGender(): array
    {
        $paths = config('sparisk.muscle_default_images');

        foreach (self::all() as $image) {
            $paths[$image->gender][$image->view] = $image->path;
        }

        return $paths;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function urlsByGender(): array
    {
        return array_map(
            fn(array $views) => array_map(fn(string $path) => asset('storage/' . $path), $views),
            self::pathsByGender(),
        );
    }
}
