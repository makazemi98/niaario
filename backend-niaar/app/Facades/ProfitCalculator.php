<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ProfitCalculator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ProfitCalculator';
    }
}
