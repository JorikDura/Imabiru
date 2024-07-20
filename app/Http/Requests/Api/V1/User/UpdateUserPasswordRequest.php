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
            'oldPassword' => ['bail', 'required', 'string', 'current_password:sanctum'],
            'newPassword' => [
                'bail',
                'required',
                'string',
                'confirmed',
                Password::default()
            ],
        ];
    }
}
