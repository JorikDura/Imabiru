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
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('testing posts', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create();
    });

    it('get all posts', function () {
        getJson('api/v1/posts')
            ->assertSuccessful();
    });

    it('add post && get it && delete it', function () {
        $testResult = actingAs($this->user)
            ->postJson(
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
            )->assertSuccessful();

        assertDatabaseHas(
            table: 'posts',
            data: [
                'user_id' => $this->user->id,
                'title' => 'test',
                'description' => 'testing!'
            ]
        );

        getJson("api/v1/posts/{$testResult->original->id}")
            ->assertSuccessful()
            ->assertSee('test');

        actingAs($this->user)
            ->delete("/api/v1/posts/{$testResult->original->id}")
            ->assertSuccessful();
    });


    it("update post", function () {
        $testResult = actingAs($this->user)
            ->postJson(
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
            )->assertSuccessful();

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
            )->assertSuccessful();

        //delete image from storage
        actingAs($this->user)
            ->deleteJson("/api/v1/posts/{$testResult->original->id}")
            ->assertSuccessful();
    });

    it("try to update && delete someone else's post", function () {
        $testResult = actingAs($this->user)
            ->postJson(
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
            )->assertSuccessful();

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
        )->assertForbidden();

        deleteJson(
            uri: "api/v1/posts/{$testResult->original->id}"
        )->assertForbidden();

        //delete image from storage
        actingAs($this->user)
            ->deleteJson("/api/v1/posts/{$testResult->original->id}")
            ->assertSuccessful();
    });

    it('get comments', function () {
        Comment::factory(5)->create([
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class
        ]);

        getJson("api/v1/posts/{$this->post->id}/comments")
            ->assertSuccessful();
    });

    it('get comment by id', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'text' => 'test comment!'
        ]);

        getJson("api/v1/posts/{$this->post->id}/comments/$comment->id")
            ->assertSuccessful()
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
            )->assertSuccessful();

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
            ->assertSuccessful();

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

    it('try to delete someone else\'s comment', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class
        ]);

        actingAs($this->user)
            ->deleteJson("/api/v1/posts/{$this->post->id}/comments/$comment->id")
            ->assertForbidden();
    });
});
