<?php

namespace App\Providers;

use App\Repository\IQuoteRepo;
use App\Repository\QuoteRepo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IQuoteRepo::class, QuoteRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
