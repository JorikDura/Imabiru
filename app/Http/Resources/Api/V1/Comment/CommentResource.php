<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Comment;

use App\Http\Resources\Api\V1\Image\ImageResource;
use App\Http\Resources\Api\V1\User\UserPreviewResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $text
 */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /* @var Comment $this */
        return [
            'id' => $this->id,
            'user' => UserPreviewResource::make($this->whenLoaded('user')),
            'text' => $this->text,
            'images' => ImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
