<?php

namespace App\Providers;

use App\Interfaces\Repositories\ShippingRepositoryInterface;
use App\Repositories\ShippingRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ShippingServiceProvider extends ServiceProvider
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
        App::bind(ShippingRepositoryInterface::class, ShippingRepository::class);
    }
}
