<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Post\UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Posts\UpdatePostRequest;
use App\Http\Resources\Api\V1\Post\PostResource;
use App\Models\Post;

class UpdatePostController extends Controller
{
    public function __invoke(
        UpdatePostRequest $request,
        UpdatePostAction $action,
        Post $post,
    ) {
        $post = $action(
            request: $request,
            post: $post
        );

        return PostResource::make($post);
    }
}
