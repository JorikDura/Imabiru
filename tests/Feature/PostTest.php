<?php

use App\Enums\TokenAbility;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestHelpers;

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

    it('add post', function () {
        Storage::fake('public');

        $testResult = actingAs($this->user)
            ->postJson(
                uri: 'api/v1/posts',
                data: [
                    'title' => 'test',
                    'description' => 'testing!',
                    'tags' => [
                        'test_tag'
                    ],
                    'images' => TestHelpers::randomUploadedFiles()
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

        TestHelpers::deleteImages($testResult);
    });

    it('get post by id', function () {
        $post = Post::factory()->create([
            'title' => 'test',
        ]);

        getJson("api/v1/posts/$post->id")
            ->assertSuccessful()
            ->assertSee('test');
    });

    it('delete post', function () {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        actingAs($this->user)
            ->delete("/api/v1/posts/$post->id")
            ->assertSuccessful();
    });

    it("update post", function () {
        $post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $testResult = actingAs($this->user)
            ->postJson(
                uri: "/api/v1/posts/$post->id",
                data: [
                    '_method' => 'PUT',
                    'title' => 'test',
                    'description' => 'still testing!',
                    'tags' => [
                        'another_tag'
                    ],
                    'images' => TestHelpers::randomUploadedFiles()
                ]
            )->assertSuccessful();

        TestHelpers::deleteImages($testResult);
    });

    it("try to update && delete someone else's post", function () {
        $post = Post::factory()->create();

        Sanctum::actingAs(
            user: User::factory()->create(),
            abilities: [TokenAbility::ACCESS_TOKEN->value]
        );

        postJson(
            uri: "/api/v1/posts/$post->id",
            data: [
                '_method' => 'PUT',
                'title' => 'test',
                'description' => 'still testing!'
            ]
        )->assertForbidden();
    });

    it('try to delete another user post', function () {
        $post = Post::factory()->create();

        Sanctum::actingAs(
            user: User::factory()->create(),
            abilities: [TokenAbility::ACCESS_TOKEN->value]
        );

        deleteJson(
            uri: "api/v1/posts/$post->id"
        )->assertForbidden();
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

    it('add post-comment', function () {
        $comment = actingAs($this->user)
            ->postJson(
                uri: "api/v1/posts/{$this->post->id}/comments",
                data: [
                    'text' => 'testing!',
                    'images' => TestHelpers::randomUploadedFiles()
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

        TestHelpers::deleteImages($comment);
    });

    it('delete comment', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'text' => 'test comment!'
        ]);

        actingAs($this->user)
            ->deleteJson("/api/v1/posts/{$this->post->id}/comments/$comment->id")
            ->assertSuccessful();

        assertDatabaseMissing(
            table: 'comments',
            data: [
                'text' => 'testing!',
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
