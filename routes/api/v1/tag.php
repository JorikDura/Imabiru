<?php

use App\Http\Controllers\Api\V1\Tag\IndexController as TagIndexController;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/tags', TagIndexController::class);
});
