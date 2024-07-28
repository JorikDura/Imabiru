<?php

use function Pest\Laravel\getJson;

describe('tag tests', function () {
    it('get tags', function () {
        getJson('/api/v1/tags/')
            ->assertSuccessful();
    });
});
