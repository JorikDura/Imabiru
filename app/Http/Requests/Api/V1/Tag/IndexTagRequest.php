<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Tag;

use Illuminate\Foundation\Http\FormRequest;

class IndexTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min:3', 'max:24'],
        ];
    }
}
