<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke(User $user)
    {
        $comments = $user
            ->comments()
            ->with(['user', 'images'])
            ->paginate();

        return CommentResource::collection($comments);
    }
}
