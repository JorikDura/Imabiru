<?php

declare(strict_types=1);

namespace App\Actions\Like;

use App\Models\Post;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

final readonly class LikeAction
{
    public function __invoke(Post $post): int
    {
        /** @var User $user */
        $user = auth()->user();

        $like = $post->likes()->where('user_id', $user->id)->exists();

        abort_if(
            boolean: $like,
            code: Response::HTTP_CONFLICT,
            message: "You already liked this post"
        );

        $post->likes()->attach($user);

        return $post->likes()->count();
    }
}
