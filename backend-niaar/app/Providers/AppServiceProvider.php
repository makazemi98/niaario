<?php

namespace App\Providers;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use App\Interfaces\Repositories\ReminderRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\ReminderRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind(BaseRepositoryInterface::class, BaseRepository::class);
    }
}
