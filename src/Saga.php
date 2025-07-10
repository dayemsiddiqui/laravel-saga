<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Models\SagaRun;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;

class Saga
{
    protected string $name = 'Unnamed Saga';

    /** @var array<class-string<\dayemsiddiqui\Saga\SagaStep>> */
    protected array $jobClasses = [];

    protected ?SagaRun $sagaRun = null;

    public static function fake(): SagaFake
    {
        $fake = new SagaFake;
        App::singleton(Saga::class, fn () => $fake);

        return $fake;
    }

    public static function named(string $name): self
    {
        $instance = App::make(Saga::class) instanceof SagaFake ? App::make(Saga::class) : new self;
        $instance->name = $name;

        return $instance;
    }

    /**
     * @param  array<class-string<\dayemsiddiqui\Saga\SagaStep>>  $jobs
     */
    public function chain(array $jobs): self
    {
        foreach ($jobs as $jobClass) {
            if (! is_subclass_of($jobClass, \dayemsiddiqui\Saga\SagaStep::class)) {
                throw new \InvalidArgumentException('All jobs must extend SagaStep');
            }
        }
        $this->jobClasses = $jobs;

        return $this;
    }

    public function dispatch(): SagaRun
    {
        $this->sagaRun = SagaRun::create([
            'name' => $this->name,
            'status' => 'started',
        ]);

        $jobs = collect($this->jobClasses)->map(function ($jobClass) {
            $step = $this->sagaRun->steps()->create([
                'name' => class_basename($jobClass),
                'status' => 'pending',
            ]);

            return new $jobClass($step);
        })->all();

        Bus::chain($jobs)->dispatch();

        return $this->sagaRun;
    }


}
