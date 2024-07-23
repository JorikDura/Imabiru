<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Actions\Image\StoreImageAction;
use App\Enums\ImagePath;
use App\Http\Requests\Api\V1\Comment\CommentRequest;
use App\Models\Comment;

final readonly class StoreCommentAction
{
    public function __construct(
        private StoreImageAction $uploadImageAction,
    ) {
    }

    public function __invoke(
        CommentRequest $request,
        int $commentableId,
        string $commentableType
    ): Comment {
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType,
            'text' => $request->validated('text')
        ]);

        $request->whenHas('images', function (array $images) use ($comment) {
            collect($images)->each(function ($image, $iteration) use ($comment) {
                $this->uploadImageAction->__invoke(
                    image: $image,
                    path: ImagePath::CommentPath,
                    name: "$comment->id-$iteration",
                    imageableId: $comment->id,
                    imageableType: Comment::class,
                );
            });

            $comment->load('images');
        });

        return tap($comment)->load([
            'user' => ['image']
        ]);
    }
}
