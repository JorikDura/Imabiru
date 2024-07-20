<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Image\UploadImageAction;
use App\Enums\ImagePath;
use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\Image\ImageRequest;
use App\Http\Resources\Api\V1\Image\ImageResource;
use App\Models\User;

class UploadUserImageController extends Controller
{
    public function __invoke(
        ImageRequest $request,
        UploadImageAction $action
    ) {
        /** @var User $user */
        $user = auth()->user();

        $image = $action(
            image: $request->validated('image'),
            path: ImagePath::UserPath,
            name: 'user-'.$user->id,
            imageableId: $user->id,
            imageableType: User::class,
        );

        return ImageResource::make($image);
    }
}
