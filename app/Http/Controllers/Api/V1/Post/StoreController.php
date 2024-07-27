<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Post;

use App\Actions\Post\StorePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Posts\StorePostRequest;
use App\Http\Resources\Api\V1\Post\PostResource;

class StoreController extends Controller
{
    public function __invoke(
        StorePostRequest $request,
        StorePostAction $action
    ) {
        $post = $action($request);

        return PostResource::make($post);
    }
}
