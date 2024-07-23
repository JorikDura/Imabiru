<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\Comment;
use App\Models\User;

class ShowUserCommentController extends Controller
{
    public function __invoke(User $user, int $id)
    {
        $comment = Comment::where([
            'id' => $id,
            'commentable_id' => $user->id,
            'commentable_type' => User::class,
        ])->with(['user', 'images'])->firstOrFail();

        return CommentResource::make($comment);
    }
}
