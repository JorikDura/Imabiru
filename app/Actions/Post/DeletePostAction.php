<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Image\DeleteImageAction;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

final readonly class DeletePostAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction,
    ) {
    }

    public function __invoke(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $images = $post->images()->get();

            $images->each(function ($image) {
                $this->deleteImageAction->__invoke($image);
            });

            $post->delete();
        });
    }
}
