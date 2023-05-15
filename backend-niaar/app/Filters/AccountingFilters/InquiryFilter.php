<?php

namespace App\Filters\AccountingFilters;

use App\Filters\Pipe;

class InquiryFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $inquiryId = $passable->filters['inquiry_id'] ?? null;

        if (!is_null($inquiryId)) {
            $passable->query->whereRelation('inquiry', 'id', $inquiryId);
        }

        return $next($passable);
    }

}
