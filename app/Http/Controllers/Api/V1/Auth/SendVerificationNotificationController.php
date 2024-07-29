<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SendVerificationNotificationController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(
            boolean: $user->hasVerifiedEmail(),
            code: Response::HTTP_FORBIDDEN,
            message: "Your email address is already verified."
        );

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification code has been sent!'
        ]);
    }
}
