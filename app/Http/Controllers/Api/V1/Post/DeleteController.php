<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Post\DeletePostAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class DeleteController extends Controller
{
    public function __invoke(Post $post, DeletePostAction $action)
    {
        $action($post);

        return response()->noContent(Response::HTTP_OK);
    }
}
