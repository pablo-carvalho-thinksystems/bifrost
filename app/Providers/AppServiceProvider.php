<?php

namespace App\Providers;

use App\Repositories\Contracts\TravelRequestRepositoryInterface;
use App\Repositories\Contracts\TravelRequestStatusRepositoryInterface;
use App\Repositories\TravelRequestRepository;
use App\Repositories\TravelRequestStatusRepository;
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
        $this->app->bind(TravelRequestRepositoryInterface::class, TravelRequestRepository::class);
        $this->app->bind(TravelRequestStatusRepositoryInterface::class, TravelRequestStatusRepository::class);
    }
}
