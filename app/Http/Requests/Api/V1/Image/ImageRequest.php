<?php

declare(strict_types=1);

namespace app\Http\Requests\Api\V1\Image;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
