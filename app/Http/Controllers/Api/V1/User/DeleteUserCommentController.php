<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Comment\DeleteCommentAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserCommentController extends Controller
{
    public function __invoke(
        int $userId,
        int $commentId,
        DeleteCommentAction $action
    ) {
        $action(
            commentId: $commentId,
            commentableId: $userId,
            commentableType: User::class,
        );

        return response()->noContent(Response::HTTP_OK);
    }
}
