<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Requests\Api\V1\User\UpdateUserPasswordRequest;
use App\Models\User;
use Exception;

final readonly class UpdateUserPasswordAction
{
    /**
     * @throws Exception
     */
    public function __invoke(UpdateUserPasswordRequest $request): void
    {
        /** @var User $user */
        $user = auth()->user();

        $user->update([
            'password' => $request->validated('newPassword')
        ]);
    }
}
