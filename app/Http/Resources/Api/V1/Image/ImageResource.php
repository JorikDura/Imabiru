<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $image_name
 * @property string $image_name_scaled
 */
class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'imageName' => asset("storage/$this->image_name"),
            'imageNameScaled' => asset("storage/$this->image_name_scaled"),
        ];
    }
}
