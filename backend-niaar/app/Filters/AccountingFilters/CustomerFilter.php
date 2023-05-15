<?php

namespace App\Filters\AccountingFilters;

use App\Filters\Pipe;

class CustomerFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $customerFilter = $passable->filters['customer_id'] ?? null;

        if (!is_null($customerFilter)) {
            $passable->query->where('customer_id', $customerFilter);
        }

        return $next($passable);
    }

}
