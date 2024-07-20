<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteImageAction
{
    public function __invoke(Image $image): void
    {
        Storage::delete("public/$image->image_name");

        if (!is_null($image->image_name_scaled)) {
            Storage::delete("public/$image->image_name_scaled");
        }

        $image->delete();
    }
}
