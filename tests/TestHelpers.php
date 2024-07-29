<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Image;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Random\RandomException;

class TestHelpers
{
    private const int IMAGE_WIDTH = 600;
    private const int IMAGE_HEIGHT = 600;

    public static function deleteImages(TestResponse $testResult): void
    {
        $testResult->original->images->each(function (Image $image) {
            Storage::disk('public')->assertExists([$image->image_name, $image->image_name_scaled]);

            Storage::disk('public')->delete([$image->image_name, $image->image_name_scaled]);

            Storage::disk('public')->assertMissing([$image->image_name, $image->image_name_scaled]);
        });
    }

    /**
     * @throws RandomException
     */
    public static function randomUploadedFiles(): array
    {
        $files = [];

        for ($i = 1; $i < random_int(2, 8); $i++) {
            $files[] = static::uploadFile("test_$i.jpg");
        }

        return $files;
    }

    public static function uploadFile(string $name): File
    {
        return UploadedFile::fake()->image(
            name: $name,
            width: self::IMAGE_WIDTH,
            height: self::IMAGE_HEIGHT
        );
    }
}
