<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Post\PostResource;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke()
    {
        $post = Post::with(['images', 'tags'])
            ->paginate();

        return PostResource::collection($post);
    }
}
