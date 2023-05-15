<?php

namespace App\Filters\AccountingFilters;

use App\Filters\Pipe;

class ClientFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $clientFilter = $passable->filters['client_id'] ?? null;

        if (!is_null($clientFilter)) {
            $passable->query->whereRelation('inquiry', 'client_id', $clientFilter);
        }

        return $next($passable);
    }

}
