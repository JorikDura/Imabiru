<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\VerifyEmailByCodeRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailByCodeController extends Controller
{
    public function __invoke(VerifyEmailByCodeRequest $request)
    {
        /** @var User $user */
        $user = auth()->user() ?? abort(code: Response::HTTP_FORBIDDEN);

        $receivedCode = $request->validated('code');
        $sentCode = Cache::get("email-{$user->getKey()}") ?? abort(
            code: Response::HTTP_FORBIDDEN,
            message: "The code is outdated."
        );

        abort_if(
            boolean: $receivedCode != $sentCode,
            code: Response::HTTP_FORBIDDEN,
            message: "Incorrect code."
        );

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            //calling event
            event(new Verified($user));
            Cache::forget("email-{$user->getKey()}");
        }

        return response()->noContent(Response::HTTP_OK);
    }
}
