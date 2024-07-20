<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
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
                    'image' => new UploadedFile(
                        path: resource_path(path: 'test-files/test_file.jpg'),
                        originalName: 'test_file.jpg',
                        test: true
                    )
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
});
