<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    //In order to make the categories available to all views
     public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('categories', Category::all());
        });

        Paginator::defaultView('pagination::default');

        Paginator::defaultSimpleView('pagination::simple-default');

        Str::macro('initials', function ($name) {
            $words = preg_split("/\s+/", $name);
            $initials = '';

            foreach (array_slice($words, 0, 2) as $word) {
                $initials .= strtoupper($word[0]);
            }

            return $initials;
        });
    }
}
