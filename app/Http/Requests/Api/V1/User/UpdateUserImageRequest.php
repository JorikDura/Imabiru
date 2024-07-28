<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
