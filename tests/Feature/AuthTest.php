<?php

use App\Enums\TokenAbility;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\post;

describe('auth test', function () {
    it('register & login & refresh', function () {
        post(
            uri: '/api/v1/auth/register',
            data: [
                'name' => 'testing',
                'email' => 'test@test.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ],
            headers: [
                'Accept' => 'application/json'
            ]
        )->assertStatus(201);

        post(
            uri: '/api/v1/auth/login',
            data: [
                'email' => 'test@test.com',
                'password' => 'password',
            ],
            headers: [
                'Accept' => 'application/json'
            ]
        )->assertStatus(200);

        Sanctum::actingAs(
            user: User::factory()->create(),
            abilities: [TokenAbility::REFRESH_TOKEN->value]
        );

        post(
            uri: '/api/v1/auth/refresh-token',
            headers: [
                'Accept' => 'application/json'
            ]
        )->assertStatus(200);
    });
});
