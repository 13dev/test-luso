<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\VersionResolver;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VersionResolver::class, function ($app) {
            return new VersionResolver($app->make(Request::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
