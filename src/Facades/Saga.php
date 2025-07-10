<?php

namespace dayemsiddiqui\Saga\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \dayemsiddiqui\Saga\SagaFake fake()
 * @method static void assertDispatched(string $sagaName, array $steps = null)
 * @method static void assertNotDispatched(string $sagaName)
 * @method static void assertDispatchedCount(int $count)
 *
 * @see \dayemsiddiqui\Saga\Saga
 */
class Saga extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \dayemsiddiqui\Saga\Saga::class;
    }
}
