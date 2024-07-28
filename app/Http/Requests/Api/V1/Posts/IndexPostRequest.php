<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Posts;

use Illuminate\Foundation\Http\FormRequest;

class IndexPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'orderByDate' => ['nullable', 'bool']
        ];
    }
}
