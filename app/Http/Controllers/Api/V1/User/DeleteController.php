<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Image\DeleteImageAction;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends Controller
{
    public function __invoke(DeleteImageAction $action)
    {
        $user = auth()->user();

        $image = $user->image()->first();

        if (!is_null($image)) {
            $action($image);
        }

        return response()->noContent(Response::HTTP_OK);
    }
}
