<?php

namespace dayemsiddiqui\Saga;


use Illuminate\Support\Facades\Bus;
use dayemsiddiqui\Saga\Models\SagaRun;
use dayemsiddiqui\Saga\Models\SagaStep as SagaStepModel;

class Saga
{
    protected string $name = 'Unnamed Saga';
    protected array $jobClasses = [];
    protected ?SagaRun $sagaRun = null;

    public static function named(string $name): self
    {
        $instance = new self();
        $instance->name = $name;
        return $instance;
    }

    public function chain(array $jobs): self
    {
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

        Bus::chain($jobs)
            ->after(fn () => $this->sagaRun->update(['status' => 'completed']))
            ->catch(fn () => $this->sagaRun->update(['status' => 'failed']))
            ->dispatch();

        return $this->sagaRun;
    }
}

