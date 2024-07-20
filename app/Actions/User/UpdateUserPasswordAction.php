<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Http\Requests\Api\V1\User\UpdateUserPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

final readonly class UpdateUserPasswordAction
{
    /**
     * @throws Exception
     */
    public function __invoke(UpdateUserPasswordRequest $request): void
    {
        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->validated('oldPassword'), $user->password)) {
            throw new Exception('Old password is incorrect');
        }

        $user->update([
            'password' => $request->validated('newPassword')
        ]);
    }
}
