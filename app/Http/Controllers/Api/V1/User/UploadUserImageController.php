<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Actions\Image\StoreImageAction;
use App\Enums\ImagePath;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\UserUpdateImageRequest;
use App\Http\Resources\Api\V1\Image\ImageResource;
use App\Models\User;

class UploadUserImageController extends Controller
{
    public function __invoke(
        UserUpdateImageRequest $request,
        StoreImageAction $action
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
