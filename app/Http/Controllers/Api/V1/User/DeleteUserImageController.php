<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Image\DeleteImageAction;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserImageController extends Controller
{
    public function __invoke(DeleteImageAction $action)
    {
        /** @var Image $image */
        $image = auth()
            ->user()
            ->image()
            ->firstOrFail();

        $action($image);

        return response()->noContent(Response::HTTP_OK);
    }
}
