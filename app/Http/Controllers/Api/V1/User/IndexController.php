<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\Filters\Api\V1\Users\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\IndexUserRequest;
use App\Http\Resources\Api\V1\User\UserPreviewResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;

class IndexController extends Controller
{
    public function __invoke(IndexUserRequest $request)
    {
        /** @var Builder $userQuery */
        $userQuery = Pipeline::send(User::query())
            ->through([
                NameFilter::class
            ])
            ->thenReturn();

        $users = $userQuery
            ->with(['image'])
            ->paginate()
            ->appends($request->query());

        return UserPreviewResource::collection($users);
    }
}
