<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\User;

use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;

describe('users test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('get users', function () {
        get('/api/v1/users/')
            ->assertStatus(200);
    });

    it('get user fail', function () {
        get('/api/v1/users/1')
            ->assertStatus(404);
    });

    it('get user', function () {
        get("/api/v1/users/{$this->user->id}")
            ->assertStatus(200);
    });

    it('upload && delete image', function () {
        actingAs($this->user)
            ->postJson(
                uri: 'api/v1/users/upload-image',
                data: [
                    'image' => getUploadedFile()
                ]
            )->assertStatus(201);

        actingAs($this->user)
            ->delete('api/v1/users/delete-image')
            ->assertStatus(200);
    });

    it('delete user', function () {
        actingAs($this->user)
            ->delete('/api/v1/users/')
            ->assertStatus(200);
    });

    it('get comments', function () {
        Comment::factory(5)->create([
            'commentable_id' => $this->user->id,
            'commentable_type' => User::class,
        ]);

        get("/api/v1/users/{$this->user->id}/comments")
            ->assertStatus(200);
    });

    it('get comment by id', function () {
        /** @var Comment $comment */
        $comment = Comment::factory()->create([
            'commentable_id' => $this->user->id,
            'commentable_type' => User::class,
            'text' => 'testing user comment!'
        ]);

        get("/api/v1/users/{$this->user->id}/comments/$comment->id")
            ->assertStatus(200)
            ->assertSee('testing user comment!');
    });

    it('add && delete comment', function () {
        $comment = actingAs($this->user)
            ->postJson(
                uri: "api/v1/users/{$this->user->id}/comments",
                data: [
                    'text' => 'testing user comment!',
                    'images' => [getUploadedFile()]
                ]
            )->assertStatus(201);

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
            ->assertStatus(200);

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
});

function getUploadedFile(): UploadedFile
{
    return new UploadedFile(
        path: resource_path(path: 'test-files/test_file.jpg'),
        originalName: 'test_file.jpg',
        test: true
    );
}
