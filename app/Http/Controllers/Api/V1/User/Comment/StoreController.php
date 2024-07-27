<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Comment;

use App\Actions\Comment\StoreCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\CommentRequest;
use App\Http\Resources\Api\V1\Comment\CommentResource;
use App\Models\User;

class StoreController extends Controller
{
    public function __invoke(
        User $user,
        CommentRequest $request,
        StoreCommentAction $action
    ) {
        $comment = $action(
            request: $request,
            commentableId: $user->id,
            commentableType: User::class
        );

        return CommentResource::make($comment);
    }
}
