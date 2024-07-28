<?php

declare(strict_types=1);

namespace App\Filters\Api\V1\Post;

use App\Http\Requests\Api\V1\Posts\IndexPostRequest;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final readonly class OrderByDateFilter
{
    public function __construct(
        private IndexPostRequest $request
    ) {
    }

    public function __invoke(Builder $builder, Closure $next)
    {
        $orderByDate = $this->request->validated('orderByDate', true);

        return $next($builder)
            ->orderBy('created_at', $orderByDate ? 'desc' : 'asc');
    }
}
