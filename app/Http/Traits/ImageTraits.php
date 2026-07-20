<?php

namespace App\Traits;

use Spatie\ImageOptimizer\OptimizerChain;

trait ImageTraits
{

    public function optimizeImage($pathToImage, $pathToOptimizedImage = null)
    {
        if (!file_exists($pathToImage)) {
            return false;
        }

        $optimizerChain = app(OptimizerChain::class);

        if ($pathToOptimizedImage !== null && file_exists($pathToOptimizedImage)) {
            $optimizerChain->optimize($pathToImage, $pathToOptimizedImage);
        } else {
            $optimizerChain->optimize($pathToImage);
        }

        return true;
    }

    public function deleteImage($pathToImage)
    {
        if (file_exists($pathToImage)) {
            unlink($pathToImage);
        }
    }

    public function storeImages($images, $directory) {
        $imagePaths = [];

        foreach ($images as $image) {
            if (!str_contains($image, $directory)) {
                if ($image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = \Str::random(20) . '.' . $extension;
                    $imagePath = $image->storeAs($directory, $filename, 'public');
                    $imagePaths[] = $imagePath;
                }
            } else {
                $imagePaths[] = $image;
            }
        }

        return $imagePaths;
    }

    public function deleteImages($images) {
        foreach ($images as $image) {
            \Storage::disk('public')->delete($image);
        }
    }
}