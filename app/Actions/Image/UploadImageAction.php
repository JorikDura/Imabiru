<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Enums\ImagePath;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image as ImageFacade;

final readonly class UploadImageAction
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

        $time = time();
        $name .= "-$time";

        $shouldScaleImage = $image->width() > self::MIN_IMAGE_HEIGHT;

        $dbImage = Image::updateOrCreate([
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType,
            'image_name' => "$path->value$name".self::FILE_EXTENSION,
            'image_name_scaled' => $shouldScaleImage
                ? "$path->value$name-scaled".self::FILE_EXTENSION
                : null,
        ]);

        $path = storage_path("app/public/$path->value");

        $image->toJpeg()
            ->save("$path$name".self::FILE_EXTENSION);

        if ($shouldScaleImage) {
            $image->scale(height: self::MIN_IMAGE_HEIGHT)
                ->toJpeg()
                ->save("$path$name-scaled".self::FILE_EXTENSION);
        }

        return $dbImage;
    }
}
