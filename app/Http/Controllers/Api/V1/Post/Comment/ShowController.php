<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke(Post $post, int $id)
    {
        $comment = Comment::where([
            'id' => $id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ])->with(['user', 'images'])->firstOrFail();

        return CommentResource::make($comment);
    }
}
