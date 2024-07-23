<?php

use App\Enums\TokenAbility;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

describe('testing posts', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
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
                        getUploadedFile()
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
            ->assertStatus(200)
            ->assertSee('test');

        actingAs($this->user)
            ->delete("/api/v1/posts/{$testResult->original->id}")
            ->assertStatus(200);
    });


    it("update post", function () {
        $testResult = actingAs($this->user)
            ->post(
                uri: 'api/v1/posts',
                data: [
                    'title' => 'another test',
                    'description' => 'testing another test!',
                    'tags' => [
                        'another_tag'
                    ],
                    'images' => [
                        getUploadedFile()
                    ]
                ]
            )->assertStatus(201);

        actingAs($this->user)
            ->postJson(
                uri: "/api/v1/posts/{$testResult->original->id}",
                data: [
                    '_method' => 'PUT',
                    'title' => 'test',
                    'tags' => [
                        'another_tag'
                    ],
                    'description' => 'still testing!'
                ]
            )->assertStatus(200);

        //delete image from storage
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
                        getUploadedFile()
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

        //delete image from storage
        actingAs($this->user)
            ->delete("/api/v1/posts/{$testResult->original->id}")
            ->assertStatus(200);
    });

    it('get comments', function () {
        Comment::factory(5)->create([
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class
        ]);

        get("api/v1/posts/{$this->post->id}/comments")
            ->assertStatus(200);
    });

    it('get comment by id', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'text' => 'test comment!'
        ]);

        get("api/v1/posts/{$this->post->id}/comments/$comment->id")
            ->assertStatus(200)
            ->assertSee('test comment!');
    });

    it('add && delete comment', function () {
        $comment = actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/{$this->post->id}/comments",
                data: [
                    'text' => 'testing!',
                    'images' => [getUploadedFile()]
                ]
            )->assertStatus(201);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'text' => 'testing!',
            ]
        );

        assertDatabaseHas(
            table: 'images',
            data: [
                'imageable_id' => $comment->original->id,
                'imageable_type' => Comment::class
            ]
        );

        actingAs($this->user)
            ->deleteJson("/api/v1/posts/{$this->post->id}/comments/{$comment->original->id}")
            ->assertStatus(200);

        assertDatabaseMissing(
            table: 'comments',
            data: [
                'text' => 'testing!',
            ]
        );

        assertDatabaseMissing(
            table: 'images',
            data: [
                'imageable_id' => $comment->original->id,
                'imageable_type' => Comment::class
            ]
        );
    });
});
