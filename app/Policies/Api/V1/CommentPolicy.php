<?php

declare(strict_types=1);

namespace App\Policies\Api\V1;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ?: null;
    }

    public function delete(User $user, Comment $comment): Response
    {
        return $user->id === $comment->user_id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this comment.');
    }
}
