<?php

namespace App\Providers;

use App\Interfaces\Repositories\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SupplierServiceProvider extends ServiceProvider
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
        App::bind(SupplierRepositoryInterface::class, SupplierRepository::class);
    }
}
