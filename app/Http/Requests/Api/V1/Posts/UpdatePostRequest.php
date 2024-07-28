<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Posts;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['bail', 'required', 'string', 'min:3', 'max:48'],
            'description' => ['bail', 'nullable', 'string', 'max:255'],
            'tags' => ['bail', 'required', 'array', 'min:1', 'max:12'],
            'tags.*' => ['bail', 'required', 'string', 'min:3', 'max:24'],
            'images' => ['bail', 'nullable', 'array', 'max:8'],
            'images.*' => ['bail', 'image', 'mimes:jpeg,jpg,png', 'max:10240'],
            'removeImagesIds' => ['bail', 'nullable', 'array'],
            'removeImagesIds.*' => ['bail', 'required', 'integer', 'exists:images,id'],
        ];
    }
}
