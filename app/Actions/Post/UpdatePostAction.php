<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Image\DeleteImageAction;
use App\Actions\Image\StoreImageAction;
use App\Enums\ImagePath;
use App\Http\Requests\Api\V1\Posts\UpdatePostRequest;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

final readonly class UpdatePostAction
{
    public function __construct(
        private StoreImageAction $uploadImageAction,
        private DeleteImageAction $deleteImageAction,
    ) {
    }

    public function __invoke(
        UpdatePostRequest $request,
        Post $post
    ): Post {
        return DB::transaction(function () use ($post, $request) {
            $post->update([
                'user_id' => auth()->id(),
                'title' => $request->validated('title'),
                'description' => $request->validated('description')
            ]);

            $tagIds = collect($request->validated('tags'))
                ->map(function ($tag) {
                    return Tag::firstOrCreate(['name' => $tag])->id;
                });

            $post->tags()->sync($tagIds);

            $request->whenHas('removeImagesIds', function (array $imagesIds) use ($post) {
                $dbImages = $post->images()
                    ->whereIn(column: 'id', values: $imagesIds)
                    ->get();

                $dbImages->each(function (Image $image) {
                    $this->deleteImageAction->__invoke($image);
                });
            });

            $request->whenHas('images', function (array $images) use ($post) {
                if (($post->images()->count() + count($images)) > 8) {
                    throw new ValidationException("There are more than 8 images.");
                }

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

            return tap($post)->load(['tags', 'images']);
        });
    }
}
