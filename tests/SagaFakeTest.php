<?php

use dayemsiddiqui\Saga\Saga;
use dayemsiddiqui\Saga\Tests\Fixtures\StepOne;
use dayemsiddiqui\Saga\Tests\Fixtures\StepTwo;

it('can fake a saga dispatch', function () {
    $fake = Saga::fake();

    Saga::named('My Test Saga')
        ->chain([
            StepOne::class,
            StepTwo::class,
        ])
        ->dispatch();

    $fake->assertDispatched('My Test Saga');
});

it('can assert a saga was dispatched with specific steps', function () {
    $fake = Saga::fake();

    Saga::named('My Test Saga')
        ->chain([
            StepOne::class,
            StepTwo::class,
        ])
        ->dispatch();

    $fake->assertDispatched('My Test Saga', [
        StepOne::class,
        StepTwo::class,
    ]);
});

it('can assert a saga was not dispatched', function () {
    $fake = Saga::fake();

    $fake->assertNotDispatched('My Test Saga');
});

it('can assert a saga was dispatched a specific number of times', function () {
    $fake = Saga::fake();

    Saga::named('My Test Saga')->dispatch();
    Saga::named('My Test Saga')->dispatch();

    $fake->assertDispatchedCount(2);
});
