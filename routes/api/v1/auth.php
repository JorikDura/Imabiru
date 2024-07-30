<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RefreshTokenController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\SendVerificationNotificationController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailByCodeController;

Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('/register', RegisterController::class);

    Route::post('/login', LoginController::class);

    Route::post('/refresh-token', RefreshTokenController::class)
        ->middleware([
            'auth:sanctum',
            'ability:'.TokenAbility::REFRESH_TOKEN->value
        ]);
    Route::group([
        'middleware' => [
            'auth:sanctum',
            'ability:'.TokenAbility::ACCESS_TOKEN->value
        ]
    ], function () {
        Route::group(['prefix' => 'email'], function () {
            Route::post('/verification-notification', SendVerificationNotificationController::class)
                ->middleware(['throttle:6,1'])
                ->name('verification.send');

            Route::post('/verification-code', VerifyEmailByCodeController::class);
        });
    });
});
