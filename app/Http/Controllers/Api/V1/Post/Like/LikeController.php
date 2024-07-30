<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Like\LikeAction;
use App\Http\Controllers\Controller;
use App\Models\Post;

class LikeController extends Controller
{
    public function __invoke(Post $post, LikeAction $action)
    {
        $likesCount = $action(post: $post);

        return response()->json([
            'likes' => $likesCount,
        ]);
    }
}
