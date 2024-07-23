<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['bail', 'nullable', 'required_without:images', 'max:255'],
            'images' => ['bail', 'nullable', 'array', 'max:8'],
            'images.*' => ['bail', 'image', 'mimes:jpeg,jpg,png', 'max:10240'],
        ];
    }
}
