<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke(Post $post)
    {
        $comments = $post
            ->comments()
            ->with(['images', 'user' => ['image']])
            ->paginate();

        return CommentResource::collection($comments);
    }
}
