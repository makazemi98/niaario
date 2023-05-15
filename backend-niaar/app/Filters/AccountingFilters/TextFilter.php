<?php

namespace App\Filters\AccountingFilters;

use App\Filters\Pipe;

class TextFilter extends Pipe
{
    public function handle($passable, \Closure $next)
    {
        $text = $passable->filters['text'] ?? null;

        if (!is_null($text)) {
            $passable->query
                ->where('description', 'like', "%{$text}%")
                ->orWhereHas('remark', function ($query) use ($text){
                    $query->where('body', 'like', "%{$text}%");
                });
        }

        return $next($passable);
    }

}
