<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'commentable_id' => $this->faker->randomNumber(),
            'commentable_type' => $this->faker->word(),
            'text' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'user_id' => User::factory(),
        ];
    }
}
