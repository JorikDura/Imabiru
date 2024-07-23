<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\CommentRequest;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\Post;

class StorePostCommentController extends Controller
{
    public function __invoke(
        Post $post,
        CommentRequest $request,
        StoreCommentAction $action
    ) {

        $comment = $action(
            request: $request,
            commentableId: $post->id,
            commentableType: Post::class
        );

        return CommentResource::make($comment);
    }
}
