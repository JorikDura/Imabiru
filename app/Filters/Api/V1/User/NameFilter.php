<?php

declare(strict_types=1);

namespace App\Filters\Api\V1\User;

use App\Http\Requests\Api\V1\User\IndexUserRequest;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Str;

final readonly class NameFilter
{
    public function __construct(
        private IndexUserRequest $request
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

                    return $builder->whereRaw(
                        sql: "search_name @@ websearch_to_tsquery('simple', ?)",
                        bindings: [$search]
                    )->orderByRaw(
                        sql: "ts_rank(search_name, websearch_to_tsquery('simple', ?))",
                        bindings: [$search]
                    );
                }
            );
    }
}
