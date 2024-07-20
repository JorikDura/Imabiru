<?php

use App\Enums\TokenAbility;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

describe('testing posts', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('get all posts', function () {
        get('api/v1/posts')
            ->assertStatus(200);
    });

    it('add post && get it && delete it', function () {
        $testResult = actingAs($this->user)
            ->post(
                uri: 'api/v1/posts',
                data: [
                    'title' => 'test',
                    'description' => 'testing!',
                    'tags' => [
                        'test_tag'
                    ],
                    'images' => [
                        new UploadedFile(
                            path: resource_path(path: 'test-files/test_file.jpg'),
                            originalName: 'test_file.jpg',
                            test: true
                        )
                    ]
                ]
            )->assertStatus(201);

        assertDatabaseHas(
            table: 'posts',
            data: [
                'user_id' => $this->user->id,
                'title' => 'test',
                'description' => 'testing!'
            ]
        );

        get("api/v1/posts/{$testResult->original->id}")
            ->assertStatus(200);

        actingAs($this->user)
            ->delete("/api/v1/posts/{$testResult->original->id}")
            ->assertStatus(200);
    });

    it("try to update && delete someone else's post", function () {
        $testResult = actingAs($this->user)
            ->post(
                uri: 'api/v1/posts',
                data: [
                    'title' => 'test',
                    'description' => 'testing!',
                    'tags' => [
                        'test_tag'
                    ],
                    'images' => [
                        new UploadedFile(
                            path: resource_path(path: 'test-files/test_file.jpg'),
                            originalName: 'test_file.jpg',
                            test: true
                        )
                    ]
                ]
            )->assertStatus(201);

        Sanctum::actingAs(
            user: User::factory()->create(),
            abilities: [TokenAbility::ACCESS_TOKEN->value]
        );

        postJson(
            uri: "/api/v1/posts/{$testResult->original->id}",
            data: [
                '_method' => 'PUT',
                'title' => 'test',
                'description' => 'still testing!'
            ]
        )->assertStatus(403);

        deleteJson(
            uri: "api/v1/posts/{$testResult->original->id}"
        )->assertStatus(403);
    });
});
