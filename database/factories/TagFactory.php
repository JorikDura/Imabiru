<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    private array $tags = [
        'test 1',
        'test 2',
        'test 3'
    ];

    public function definition(): array
    {
        return [
            'name' => $this->tags[array_rand($this->tags)],
        ];
    }
}
