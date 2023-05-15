<?php

namespace App\Providers;

use App\Interfaces\Repositories\ReminderRepositoryInterface;
use App\Repositories\ReminderRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ReminderServiceProvider extends ServiceProvider
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
        App::bind(ReminderRepositoryInterface::class, ReminderRepository::class);
        //
    }
}
