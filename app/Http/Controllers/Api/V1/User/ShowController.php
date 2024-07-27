<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;

class ShowController extends Controller
{
    public function __invoke(int $id)
    {
        $user = User::where('id', $id)
            ->with(['image'])
            ->firstOrFail();

        return UserResource::make($user);
    }
}
