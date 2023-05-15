<?php

namespace App\Filters;

class CreatorFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $creatorId = $passable->filters['creator_id'] ?? null;

        if (!is_null($creatorId)) {
            $passable->query
                ->where('created_by', $creatorId);
        }

        return $next($passable);
    }

}
