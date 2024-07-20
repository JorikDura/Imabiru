<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\User\UpdateUserEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UpdateUserEmailRequest;
use App\Http\Resources\Api\V1\User\UserResource;

class UpdateUserEmailController extends Controller
{
    public function __invoke(
        UpdateUserEmailRequest $request,
        UpdateUserEmailAction $action
    ) {
        $user = $action($request);

        return UserResource::make($user);
    }
}
