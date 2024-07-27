<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\User\UpdateUserPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UpdateUserPasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePasswordController extends Controller
{
    /**
     * @throws \Exception
     */
    public function __invoke(
        UpdateUserPasswordRequest $request,
        UpdateUserPasswordAction $action
    ) {
        $action($request);

        return response()->noContent(Response::HTTP_OK);
    }
}
