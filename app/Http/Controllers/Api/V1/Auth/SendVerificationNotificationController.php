<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendVerificationNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link has been sent!'
        ]);
    }
}
