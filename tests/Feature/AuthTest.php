<?php

use App\Enums\TokenAbility;
use App\Models\User;
use App\Notifications\VerifyEmailByCode;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

describe('auth test', function () {
    it('register & login & refresh', function () {
        postJson(
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
        )->assertSuccessful();

        postJson(
            uri: '/api/v1/auth/login',
            data: [
                'email' => 'test@test.com',
                'password' => 'password',
            ],
            headers: [
                'Accept' => 'application/json'
            ]
        )->assertSuccessful();

        Sanctum::actingAs(
            user: User::factory()->create(),
            abilities: [TokenAbility::REFRESH_TOKEN->value]
        );

        postJson(
            uri: '/api/v1/auth/refresh-token',
            headers: [
                'Accept' => 'application/json'
            ]
        )->assertSuccessful();
    });

    it('send email notification', function () {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        Sanctum::actingAs(
            user: $user,
            abilities: [TokenAbility::ACCESS_TOKEN->value]
        );

        postJson(route('verification.send'))
            ->assertSuccessful();

        Notification::assertSentTo($user, VerifyEmailByCode::class);
    });
});
