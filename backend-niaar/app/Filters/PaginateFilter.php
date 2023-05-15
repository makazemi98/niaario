<?php

namespace App\Filters;

class PaginateFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $passable->query->paginate($passable->filters['per_page'] ?? 15);

        return $next($passable);
    }

}
