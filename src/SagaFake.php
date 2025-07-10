<?php

namespace dayemsiddiqui\Saga;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class SagaFake extends Saga
{
    /**
     * @var array<int, object{name: string, jobs: array<class-string<\dayemsiddiqui\Saga\SagaStep>>}>
     */
    protected array $dispatchedSagas = [];

    public function dispatch(): Models\SagaRun
    {
        $this->dispatchedSagas[] = (object) [
            'name' => $this->name,
            'jobs' => $this->jobClasses,
        ];

        // We're returning a dummy SagaRun model here.
        // In a real scenario, you might want to mock this as well.
        return new Models\SagaRun(['name' => $this->name, 'status' => 'faked']);
    }

    public function assertDispatched(string $sagaName, ?array $steps = null): void
    {
        PHPUnit::assertTrue(
            $this->dispatched($sagaName, $steps)->count() > 0,
            "The expected saga [{$sagaName}] was not dispatched."
        );
    }

    public function assertNotDispatched(string $sagaName): void
    {
        PHPUnit::assertTrue(
            $this->dispatched($sagaName)->count() === 0,
            "The unexpected saga [{$sagaName}] was dispatched."
        );
    }

    public function assertDispatchedCount(int $count): void
    {
        PHPUnit::assertCount($count, $this->dispatchedSagas);
    }

    /**
     * @param  array<class-string<\dayemsiddiqui\Saga\SagaStep>>|null  $steps
     */
    protected function dispatched(string $sagaName, ?array $steps = null): Collection
    {
        return collect($this->dispatchedSagas)->filter(function ($saga) use ($sagaName, $steps) {
            if ($saga->name !== $sagaName) {
                return false;
            }

            if ($steps) {
                return $saga->jobs == $steps;
            }

            return true;
        });
    }
}
