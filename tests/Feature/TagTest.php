<?php

use App\Models\Tag;

use function Pest\Laravel\getJson;

describe('tag tests', function () {
    it('get tags', function () {
        Tag::factory()->create([
            'name' => 'testing!'
        ]);

        getJson('/api/v1/tags/')
            ->assertSuccessful()
            ->assertSee('testing!');
    });
});
