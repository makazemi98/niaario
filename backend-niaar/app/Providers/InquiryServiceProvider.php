<?php

namespace App\Providers;

use App\Interfaces\Repositories\InquiryRepositoryInterface;
use App\Repositories\InquiryRepository;
use App\Services\ProfitCalculator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class InquiryServiceProvider extends ServiceProvider
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
        App::bind(InquiryRepositoryInterface::class, InquiryRepository::class);
        App::bind('ProfitCalculator', function () {
            return new ProfitCalculator();
        });
    }
}
