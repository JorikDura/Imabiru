<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Requests\Api\V1\User\UpdateUserEmailRequest;
use App\Models\User;

final readonly class UpdateUserEmailAction
{
    public function __invoke(UpdateUserEmailRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        return tap($user)->update([
            'email' => $request->validated('email')
        ]);
    }
}
