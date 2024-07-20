<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateTokenAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request, CreateTokenAction $action)
    {
        $user = Auth::user();
        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();
        $token->delete();

        /** @var array{token: string, refreshToken: string} $tokens */
        $tokens = $action($user);

        return response()->json(
            data: [
                'token' => $tokens['token'],
                'refreshToken' => $tokens['refreshToken']
            ]
        );
    }
}
