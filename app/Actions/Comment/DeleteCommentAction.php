<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Support\Facades\Gate;

final readonly class DeleteCommentAction
{
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

        Gate::authorize('delete', $comment);

        $images = $comment->images()->get();

        $images->each(fn (Image $image) => $image->delete());

        $comment->delete();
    }
}
