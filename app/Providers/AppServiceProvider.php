<?php

namespace App\Providers;

use App\Services\Plans\ExternalPlansClient;
use App\Services\Plans\ExternalPlansClientInterface;
use App\Services\Plans\ExternalPlansContentParser;
use App\Services\Plans\ExternalPlansParserInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ExternalPlansParserInterface::class => ExternalPlansContentParser::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExternalPlansClientInterface::class, function () {
            return new ExternalPlansClient(config('app.PLANS_PROVIDER_API_URL', ''));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
