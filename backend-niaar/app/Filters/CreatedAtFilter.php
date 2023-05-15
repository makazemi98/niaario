<?php

namespace App\Filters;

class CreatedAtFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $from = $passable->filters['from_created_at'] ?? null;
        $to = $passable->filters['to_created_at'] ?? null;

        $passable->query
            ->when(
                !is_null($from) && is_null($to),
                function ($query) use ($from) {
                    $query->whereDate('created_at', '>=', $from);
                }
            )
            ->when(
                is_null($from) && !is_null($to),
                function ($query) use ($to) {
                    $query->whereDate('created_at', '<=', $to);
                }
            )
            ->when(
                !is_null($from) && !is_null($to),
                function ($query) use ($from, $to) {
                    $query->whereBetween(
                        'created_at',
                        [
                            $from,
                            $to
                        ]
                    );
                }
            );

        return $next($passable);
    }

}
