<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Post\PostResource;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke(int $id)
    {
        $post = Post::with(['tags', 'images'])
            ->withCount('likes')
            ->findOrFail($id);

        return PostResource::make($post);
    }
}
