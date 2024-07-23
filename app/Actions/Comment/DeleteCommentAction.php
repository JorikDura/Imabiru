<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Actions\Image\DeleteImageAction;
use App\Models\Comment;

final readonly class DeleteCommentAction
{
    public function __construct(
        private DeleteImageAction $deleteImageAction
    ) {
    }

    public function __invoke(
        int $commentId,
        int $commentableId,
        string $commentableType
    ): void {
        $comment = Comment::where([
            'id' => $commentId,
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType
        ])->firstOrFail();

        $images = $comment->images()->get();

        $images->each(function ($image) {
            $this->deleteImageAction->__invoke($image);
        });

        $comment->delete();
    }
}
