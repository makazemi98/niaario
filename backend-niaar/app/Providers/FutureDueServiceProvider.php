<?php

namespace App\Providers;

use App\Interfaces\Repositories\FutureDueRepositoryInterface;
use App\Repositories\FutureDueRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FutureDueServiceProvider extends ServiceProvider
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
        App::bind(FutureDueRepositoryInterface::class, FutureDueRepository::class);
    }
}
