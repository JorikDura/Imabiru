<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailController extends Controller
{
    public function __invoke(int $id, string $hash)
    {
        $user = User::find($id);

        abort_if(
            boolean: is_null($user),
            code: Response::HTTP_FORBIDDEN
        );

        abort_if(
            boolean: !hash_equals($hash, sha1($user->getEmailForVerification())),
            code: Response::HTTP_FORBIDDEN
        );

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            //calling event
            event(new Verified($user));
        }

        return 'Все хорошо';
    }
}
