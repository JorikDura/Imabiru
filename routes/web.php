<?php

use App\Http\Controllers\Web\VerifyEmailController;

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->name('verification.verify');
