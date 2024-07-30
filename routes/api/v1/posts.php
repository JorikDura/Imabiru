<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Post\Comment\DeleteController as CommentDeleteController;
use App\Http\Controllers\Api\V1\Post\Comment\IndexController as CommentIndexController;
use App\Http\Controllers\Api\V1\Post\Comment\ShowController as CommentShowController;
use App\Http\Controllers\Api\V1\Post\Comment\StoreController as CommentStoreController;
use App\Http\Controllers\Api\V1\Post\DeleteController as PostDeleteController;
use App\Http\Controllers\Api\V1\Post\IndexController as PostIndexController;
use App\Http\Controllers\Api\V1\Post\Like\DislikeController as PostDislikeController;
use App\Http\Controllers\Api\V1\Post\Like\LikeController as PostLikeLikeController;
use App\Http\Controllers\Api\V1\Post\ShowController as PostShowController;
use App\Http\Controllers\Api\V1\Post\StoreController as PostStoreController;
use App\Http\Controllers\Api\V1\Post\UpdateController as PostUpdateController;

Route::group(['prefix' => 'v1/posts'], function () {
    Route::get('/', PostIndexController::class);

    Route::get('/{id}', PostShowController::class);

    Route::group(['prefix' => '{post}/comments'], function () {
        Route::get('/', CommentIndexController::class);

        Route::get('/{id}', CommentShowController::class);
    });

    Route::group([
        'middleware' => [
            'auth:sanctum',
            'verified',
            'ability:'.TokenAbility::ACCESS_TOKEN->value
        ]
    ], function () {
        Route::post('/', PostStoreController::class)
            ->middleware(['throttle:6,15']);

        Route::put('/{post}', PostUpdateController::class)
            ->can('update', 'post')
            ->middleware(['throttle:6,15']);

        Route::delete('/{post}', PostDeleteController::class)
            ->can('delete', 'post')
            ->middleware(['throttle:10,15']);

        Route::post('/{post}/like', PostLikeLikeController::class);

        Route::post('/{post}/dislike', PostDislikeController::class);

        Route::group(['prefix' => '{post}/comments'], function () {
            Route::post('/', CommentStoreController::class)
                ->middleware(['throttle:6,1']);

            Route::delete('/{commentId}', CommentDeleteController::class)
                ->middleware(['throttle:6,3']);
        });
    });
});
