<?php

use dayemsiddiqui\Saga\Saga;
use dayemsiddiqui\Saga\Tests\Fixtures\StepOne;
use dayemsiddiqui\Saga\Tests\Fixtures\StepTwo;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can set context on saga', function () {
    $saga = Saga::named('Test Saga');
    $contextData = ['user_id' => 123, 'order_id' => 456];
    
    $result = $saga->context($contextData);
    
    expect($result)->toBeInstanceOf(Saga::class);
    expect($saga)->toBe($result);
});

it('can chain context method with other methods', function () {
    $fake = Saga::fake();
    
    Saga::named('Test Saga')
        ->context(['user_id' => 123])
        ->chain([StepOne::class, StepTwo::class])
        ->dispatch();
    
    $fake->assertDispatched('Test Saga');
});

it('persists context data when dispatching saga', function () {
    $contextData = ['user_id' => 123, 'order_id' => 456];
    
    $sagaRun = Saga::named('Test Saga')
        ->context($contextData)
        ->chain([StepOne::class])
        ->dispatch();
    
    expect($sagaRun->context)->toBe($contextData);
});

it('can handle empty context', function () {
    $sagaRun = Saga::named('Test Saga')
        ->context([])
        ->chain([StepOne::class])
        ->dispatch();
    
    expect($sagaRun->context)->toBe([]);
});

it('can handle complex context data', function () {
    $contextData = [
        'user' => ['id' => 123, 'name' => 'John'],
        'order' => ['id' => 456, 'total' => 99.99],
        'metadata' => ['source' => 'web', 'timestamp' => now()->toISOString()],
    ];
    
    $sagaRun = Saga::named('Test Saga')
        ->context($contextData)
        ->chain([StepOne::class])
        ->dispatch();
    
    expect($sagaRun->context)->toBe($contextData);
});
