<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Models\SagaStep as SagaStepModel;
use dayemsiddiqui\Saga\Models\SagaStepStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Throwable;

abstract class SagaStep implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SagaStepModel $step;

    protected SagaContext $context;

    public function __construct(SagaStepModel $step)
    {
        $this->step = $step;
        $this->context = new SagaContext($this->step->sagaRun);
    }

    public function __get($name)
    {
        return $name === 'context' ? $this->context() : ($this->$name ?? null);
    }

    public function context(): SagaContext
    {
        return $this->context;
    }

    public function handle()
    {
        $this->step->setStatus(SagaStepStatus::STARTED);
        $this->step->save();

        try {
            $this->run();
            $this->step->setStatus(SagaStepStatus::COMPLETED);
            $this->step->save();
        } catch (Throwable $e) {
            $this->step->setStatus(SagaStepStatus::FAILED);
            $this->step->error_message = $e->getMessage();
            $this->step->save();
            throw $e;
        }
    }

    abstract protected function run(): void;
}
