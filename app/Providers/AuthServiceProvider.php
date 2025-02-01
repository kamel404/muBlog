<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Post Authorization Gates
        Gate::define('update-post', function (User $user, Post $post) {
            return $user->id === $post->user_id || $user->isAdmin();
        });

        Gate::define('delete-post', function (User $user, Post $post) {
            return $user->id === $post->user_id || $user->isAdmin();
        });
    }
}
