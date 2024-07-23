<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Policies\Api\V1\CommentPolicy;
use App\Policies\Api\V1\PostPolicy;
use Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
