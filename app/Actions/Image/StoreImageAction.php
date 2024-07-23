<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Enums\ImagePath;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

final readonly class StoreImageAction
{
    private const int MIN_IMAGE_HEIGHT = 400;

    private const string FILE_EXTENSION = '.jpg';

    public function __invoke(
        UploadedFile $image,
        ImagePath $path,
        string $name,
        int $imageableId,
        string $imageableType
    ): Image {
        $image = ImageFacade::read($image);

        $name .= '-'.time();

        $shouldScaleImage = $image->width() > self::MIN_IMAGE_HEIGHT;

        $dbImage = Image::updateOrCreate([
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
            'image_name' => "$path->value$name".self::FILE_EXTENSION,
            'image_name_scaled' => $shouldScaleImage
                ? "$path->value$name-scaled".self::FILE_EXTENSION
                : null,
        ]);

        /** @var UploadedFile $convertedOriginalImage */
        $convertedOriginalImage = $image->toJpeg();

        Storage::disk(name: 'public')
            ->put(
                path: "$path->value$name".self::FILE_EXTENSION,
                contents: $convertedOriginalImage
            );

        if ($shouldScaleImage) {
            /** @var UploadedFile $convertedScaledImage */
            $convertedScaledImage = $image->scale(height: self::MIN_IMAGE_HEIGHT)->toJpeg();

            Storage::disk(name: 'public')
                ->put(
                    path: "$path->value$name-scaled".self::FILE_EXTENSION,
                    contents: $convertedScaledImage
                );
        }

        return $dbImage;
    }
}
