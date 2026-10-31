<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * The public storage disk is not in git, so the default images the seeders
 * point at (muscle body pictures, treatment plan pictures) are kept in
 * `database/seeders/images` and copied onto the disk when they are missing.
 * A file already on the disk is left alone, so an image an admin replaced is
 * never overwritten.
 */
trait PublishesSeedImages
{
    protected function publishSeedImages(string $directory): void
    {
        $source = database_path("seeders/images/{$directory}");
        if (!File::isDirectory($source)) {
            return;
        }

        $disk = Storage::disk('public');

        foreach (File::files($source) as $file) {
            $target = "{$directory}/{$file->getFilename()}";
            if (!$disk->exists($target)) {
                $disk->put($target, File::get($file->getPathname()));
            }
        }
    }
}
