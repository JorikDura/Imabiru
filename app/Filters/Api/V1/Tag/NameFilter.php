<?php

declare(strict_types=1);

namespace App\Filters\Api\V1\Tag;

use App\Http\Requests\Api\V1\Tag\IndexTagRequest;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Str;

final readonly class NameFilter
{
    public function __construct(
        private IndexTagRequest $request
    ) {
    }

    public function __invoke(Builder $builder, Closure $next)
    {
        return $next($builder)
            ->when(
                $this->request->has('search'),
                function (Builder $builder) {
                    $search = Str::of($this->request->validated('search'))
                        ->trim();

                    return $builder->where('name', 'like', "%$search%");
                }
            );
    }
}
