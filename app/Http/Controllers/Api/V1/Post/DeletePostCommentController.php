<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Comment\DeleteCommentAction;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class DeletePostCommentController extends Controller
{
    public function __invoke(
        int $postId,
        int $commentId,
        DeleteCommentAction $action
    ): Response {
        $action(
            commentId: $commentId,
            commentableId: $postId,
            commentableType: Post::class,
        );

        return response()->noContent(Response::HTTP_OK);
    }
}
