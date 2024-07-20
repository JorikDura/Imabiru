<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'oldPassword' => ['bail', 'required_with:newPassword', 'nullable', 'string'],
            'newPassword' => [
                'bail',
                'required_with:password',
                'nullable',
                'string',
                'confirmed',
                Password::default()
            ],
        ];
    }
}
