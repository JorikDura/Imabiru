<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Image;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends Controller
{
    public function __invoke()
    {
        /** @var User $user */
        $user = auth()->user();

        $user->image()
            ->firstOrFail()
            ->delete();

        return response()->noContent(Response::HTTP_OK);
    }
}
