<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserResource;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
        $users = User::with(['image'])
            ->paginate();

        return UserResource::collection($users);
    }
}
