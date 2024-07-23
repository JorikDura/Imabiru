<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RefreshTokenController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Post\DeletePostController;
use App\Http\Controllers\Api\V1\Post\IndexPostController;
use App\Http\Controllers\Api\V1\Post\ShowPostController;
use App\Http\Controllers\Api\V1\Post\StorePostController;
use App\Http\Controllers\Api\V1\Post\UpdatePostController;
use App\Http\Controllers\Api\V1\User\DeleteUserController;
use App\Http\Controllers\Api\V1\User\DeleteUserImageController;
use App\Http\Controllers\Api\V1\User\IndexUserController;
use App\Http\Controllers\Api\V1\User\ShowUserController;
use App\Http\Controllers\Api\V1\User\UpdateUserEmailController;
use App\Http\Controllers\Api\V1\User\UpdateUserPasswordController;
use App\Http\Controllers\Api\V1\User\UploadUserImageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', RegisterController::class);
        Route::post('/login', LoginController::class);
        Route::post('/refresh-token', RefreshTokenController::class)
            ->middleware([
                'auth:sanctum',
                'ability:'.TokenAbility::REFRESH_TOKEN->value
            ]);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', IndexUserController::class);
        Route::get('/{id}', ShowUserController::class);
    });

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', IndexPostController::class);
        Route::get('/{id}', ShowPostController::class);
    });

    Route::group([
        'middleware' => [
            'auth:sanctum',
            'ability:'.TokenAbility::ACCESS_TOKEN->value
        ]
    ], function () {
        Route::group(['prefix' => 'users'], function () {
            Route::post('/upload-image', UploadUserImageController::class)
                ->middleware(['throttle:3,15']);
            Route::delete('/delete-image', DeleteUserImageController::class)
                ->middleware(['throttle:3,15']);
            Route::put('/update-password', UpdateUserPasswordController::class)
                ->middleware(['throttle:1,1440']);
            Route::put('/update-email', UpdateUserEmailController::class)
                ->middleware(['throttle:1,1440']);
            Route::delete('/', DeleteUserController::class);
        });

        Route::group(['prefix' => 'posts'], function () {
            Route::post('/', StorePostController::class)
                /*->middleware(['throttle:6,15'])*/;
            Route::put('/{post}', UpdatePostController::class)
                ->can('update', 'post')
                ->middleware(['throttle:6,15']);
            Route::delete('/{post}', DeletePostController::class)
                ->can('delete', 'post')
                ->middleware(['throttle:10,15']);
        });
    });
});
