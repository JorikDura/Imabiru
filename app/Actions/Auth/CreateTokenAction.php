<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\TokenAbility;
use App\Models\User;
use Carbon\Carbon;

final readonly class CreateTokenAction
{
    public const string TOKEN_CONFIG_KEY = 'sanctum.expiration';
    public const string REFRESH_TOKEN_CONFIG_KEY = 'sanctum.rt_expiration';

    /**
     * Returns tokens (access token && refresh token)
     * @param  User  $user
     * @return array
     */
    public function __invoke(User $user): array
    {
        $accessToken = $user->createToken(
            name: 'access_token',
            abilities: [TokenAbility::ACCESS_TOKEN->value],
            expiresAt: Carbon::now()->addMinutes(config(self::TOKEN_CONFIG_KEY))
        );

        $refreshToken = $user->createToken(
            name: 'access_token',
            abilities: [TokenAbility::REFRESH_TOKEN->value],
            expiresAt: Carbon::now()->addMinutes(config(self::REFRESH_TOKEN_CONFIG_KEY))
        );

        return [
            'token' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }
}
