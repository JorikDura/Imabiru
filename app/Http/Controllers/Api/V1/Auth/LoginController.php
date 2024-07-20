<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, CreateTokenAction $action)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(
                data: [
                    'message' => 'These credentials do not match our records.'
                ],
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = Auth::user();

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
