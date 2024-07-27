<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\User\Comment\DeleteController as CommentDeleteController;
use App\Http\Controllers\Api\V1\User\Comment\IndexController as CommentIndexController;
use App\Http\Controllers\Api\V1\User\Comment\ShowController as CommentShowController;
use App\Http\Controllers\Api\V1\User\Comment\StoreController as CommentStoreController;
use App\Http\Controllers\Api\V1\User\DeleteController as UserDeleteController;
use App\Http\Controllers\Api\V1\User\Image\DeleteController as ImageDeleteController;
use App\Http\Controllers\Api\V1\User\Image\UploadController as ImageUploadController;
use App\Http\Controllers\Api\V1\User\IndexController as UserIndexController;
use App\Http\Controllers\Api\V1\User\ShowController as UserShowController;
use App\Http\Controllers\Api\V1\User\UpdateEmailController as UserUpdateEmailController;
use App\Http\Controllers\Api\V1\User\UpdatePasswordController as UserUpdatePasswordController;

Route::group(['prefix' => 'v1/users'], function () {
    Route::get('/', UserIndexController::class);

    Route::get('/{id}', UserShowController::class);

    Route::group(['prefix' => '{user}/comments'], function () {
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
        Route::post('/upload-image', ImageUploadController::class)
            ->middleware(['throttle:3,15']);

        Route::delete('/delete-image', ImageDeleteController::class)
            ->middleware(['throttle:3,15']);

        Route::put('/update-password', UserUpdatePasswordController::class)
            ->middleware(['throttle:1,1440']);

        Route::put('/update-email', UserUpdateEmailController::class)
            ->middleware(['throttle:1,1440']);

        Route::delete('/', UserDeleteController::class);

        Route::group(['prefix' => '{user}/comments'], function () {
            Route::post('/', CommentStoreController::class)
                ->middleware(['throttle:6,1']);

            Route::delete('{commentId}', CommentDeleteController::class)
                ->middleware(['throttle:6,3']);
        });
    });
});
