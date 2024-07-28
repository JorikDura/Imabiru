<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Tag;

use App\Filters\Api\V1\Tag\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tag\IndexTagRequest;
use App\Http\Resources\Api\V1\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;

class IndexController extends Controller
{
    public function __invoke(IndexTagRequest $request)
    {
        /** @var Builder $tagsQuery */
        $tagsQuery = Pipeline::send(Tag::query())
            ->through([
                NameFilter::class
            ])
            ->thenReturn();

        $tags = $tagsQuery->paginate();

        return TagResource::collection($tags);
    }
}
