<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Post;

use App\Http\Resources\Api\V1\Image\ImageResource;
use App\Http\Resources\Api\V1\Tag\TagResource;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /* @var Post $this */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
