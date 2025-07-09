<?php

namespace dayemsiddiqui\Saga\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \dayemsiddiqui\Saga\Saga
 */
class Saga extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \dayemsiddiqui\Saga\Saga::class;
    }
}
