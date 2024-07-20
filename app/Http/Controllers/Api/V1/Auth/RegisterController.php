<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, CreateTokenAction $action)
    {
        $user = User::create($request->validated());

        /** @var array{token: string, refreshToken: string} $tokens */
        $tokens = $action($user);

        return response()->json(
            data: [
                'token' => $tokens['token'],
                'refreshToken' => $tokens['refreshToken']
            ],
            status: Response::HTTP_CREATED
        );
    }
}
