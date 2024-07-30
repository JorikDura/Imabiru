<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Image\StoreImageAction;
use App\Enums\ImagePath;
use App\Http\Requests\Api\V1\Posts\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Str;

final readonly class StorePostAction
{
    public function __construct(
        private StoreImageAction $uploadImageAction,
    ) {
    }

    public function __invoke(StorePostRequest $request): Post
    {
        return DB::transaction(function () use ($request) {
            $post = Post::create([
                'user_id' => auth()->id(),
                'title' => $request->validated('title'),
                'description' => $request->validated('description')
            ]);

            $tagIds = collect($request->validated('tags'))
                ->map(function ($tag) {
                    return Tag::firstOrCreate(['name' => Str::lower($tag)])->id;
                });

            $post->tags()->attach($tagIds);

            $request->whenHas('images', function (array $images) use ($post) {
                collect($images)->each(function ($image, $iteration) use ($post) {
                    $this->uploadImageAction->__invoke(
                        image: $image,
                        path: ImagePath::PostPath,
                        name: "$post->id-$iteration",
                        imageableId: $post->id,
                        imageableType: Post::class
                    );
                });
            });

            return tap($post)->load([
                'images',
                'tags'
            ])->loadCount('likes');
        });
    }
}
