<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

final readonly class DeletePostAction
{
    public function __invoke(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $images = $post->images()->get();

            $images->each(function (Image $image) {
                $image->delete();
            });

            $post->delete();
        });
    }
}
