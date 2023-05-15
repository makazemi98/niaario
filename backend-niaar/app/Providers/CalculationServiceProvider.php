<?php

namespace App\Providers;

use App\Interfaces\Repositories\CalculationRepositoryInterface;
use App\Repositories\CalculationRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CalculationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind(CalculationRepositoryInterface::class, CalculationRepository::class);
    }
}
