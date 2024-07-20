<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\User;

use App\Http\Resources\Api\V1\Image\ImageResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $created_at
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /* @var User $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => ImageResource::make($this->whenLoaded('image')) ,
            'created_at' => $this->created_at,
        ];
    }
}
