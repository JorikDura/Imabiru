<?php

declare(strict_types=1);

namespace App\Filters\Api\V1\Post;

use App\Http\Requests\Api\V1\Posts\PostIndexRequest;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final readonly class TagFilter
{
    public function __construct(
        private PostIndexRequest $request
    ) {
    }

    public function __invoke(Builder $builder, Closure $next)
    {
        return $next($builder)
            ->when(
                $this->request->has('tags'),
                function (Builder $builder) {
                    $tags = $this->request->validated('tags');

                    return $builder->withWhereHas(
                        relation: 'tags',
                        callback: fn (Builder $b) => $b->whereIn('name', $tags)
                    );
                }
            );
    }
}
