<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post\Like;

use App\Actions\Like\DislikeAction;
use App\Http\Controllers\Controller;
use App\Models\Post;

class DislikeController extends Controller
{
    public function __invoke(Post $post, DislikeAction $action)
    {
        $likesCount = $action(post: $post);

        return response()->json([
            'likes' => $likesCount,
        ]);
    }
}
