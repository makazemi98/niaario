<?php

namespace App\Filters\AccountingFilters;

use App\Enums\BalanceSheetCategoryEnum;
use App\Filters\Pipe;

class CategoryFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $category = $passable->filters['category'] ?? null;

        $passable->query
            ->when(
                !is_null($category) && in_array($category, BalanceSheetCategoryEnum::values()),
                fn($query) => $query->where('category', $category)
            );

        return $next($passable);
    }

}
