<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\User;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('users test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('get users', function () {
        getJson('/api/v1/users/')
            ->assertSuccessful();
    });

    it('get user fail', function () {
        getJson('/api/v1/users/1')
            ->assertStatus(404);
    });

    it('get user', function () {
        getJson("/api/v1/users/{$this->user->id}")
            ->assertSuccessful();
    });

    it('upload && delete image', function () {
        $testResult = actingAs($this->user)
            ->postJson(
                uri: 'api/v1/users/upload-image',
                data: [
                    'image' => TestHelpers::uploadFile('test_1.jpg')
                ]
            )->assertSuccessful();

        Storage::disk('public')->assertExists([
            $testResult->original->image_name,
            $testResult->original->image_name_scaled
        ]);

        actingAs($this->user)
            ->deleteJson('api/v1/users/delete-image')
            ->assertSuccessful();

        Storage::disk('public')->assertMissing([
            $testResult->original->image_name,
            $testResult->original->image_name_scaled
        ]);
    });

    it('delete user', function () {
        actingAs($this->user)
            ->deleteJson('/api/v1/users/')
            ->assertSuccessful();
    });

    it('get comments', function () {
        Comment::factory(5)->create([
            'commentable_id' => $this->user->id,
            'commentable_type' => User::class,
        ]);

        getJson("/api/v1/users/{$this->user->id}/comments")
            ->assertSuccessful();
    });

    it('get comment by id', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'commentable_id' => $this->user->id,
            'commentable_type' => User::class,
            'text' => 'testing user comment!'
        ]);

        getJson("/api/v1/users/{$this->user->id}/comments/$comment->id")
            ->assertSuccessful()
            ->assertSee('testing user comment!');
    });

    it('add && delete comment', function () {
        $comment = actingAs($this->user)
            ->postJson(
                uri: "api/v1/users/{$this->user->id}/comments",
                data: [
                    'text' => 'testing user comment!',
                    'images' => TestHelpers::randomUploadedFiles()
                ]
            )->assertSuccessful();

        assertDatabaseHas(
            table: 'comments',
            data: [
                'text' => 'testing user comment!',
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
            ->deleteJson("/api/v1/users/{$this->user->id}/comments/{$comment->original->id}")
            ->assertSuccessful();

        assertDatabaseMissing(
            table: 'comments',
            data: [
                'text' => 'testing user comment!',
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
            'commentable_id' => $this->user->id,
            'commentable_type' => User::class
        ]);

        actingAs($this->user)
            ->deleteJson("/api/v1/users/{$this->user->id}/comments/$comment->id")
            ->assertForbidden();
    });
});
