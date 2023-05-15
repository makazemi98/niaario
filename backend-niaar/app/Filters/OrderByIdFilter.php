<?php

namespace App\Filters;

class OrderByIdFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $passable->query->orderBy('id', $passable->filters['order'] ?? 'desc');

        return $next($passable);
    }

}
