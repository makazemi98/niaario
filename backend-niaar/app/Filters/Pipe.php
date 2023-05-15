<?php

namespace App\Filters;

abstract class Pipe
{
    abstract public function handle($passable, \Closure $next);
}
