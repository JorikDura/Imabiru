<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Filters\Api\V1\Post\OrderByDateFilter;
use App\Filters\Api\V1\Post\TagFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Posts\IndexPostRequest;
use App\Http\Resources\Api\V1\Post\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;

class IndexController extends Controller
{
    public function __invoke(IndexPostRequest $request)
    {
        /** @var Builder $postQuery */
        $postQuery = Pipeline::send(Post::query())
            ->through([
                TagFilter::class,
                OrderByDateFilter::class
            ])
            ->thenReturn();

        $post = $postQuery
            ->with(['images', 'tags'])
            ->withCount('likes')
            ->paginate();

        return PostResource::collection($post);
    }
}
