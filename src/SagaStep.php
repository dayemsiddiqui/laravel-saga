<?php
namespace dayemsiddiqui\Saga;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use dayemsiddiqui\Saga\Models\SagaStep as SagaStepModel;
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
        $this->step->update(['status' => 'processing']);

        try {
            $this->run();
            $this->step->update(['status' => 'success']);
        } catch (Throwable $e) {
            $this->step->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            $this->step->sagaRun->update(['status' => 'failed']);
            throw $e;
        }
    }

    abstract protected function run(): void;
}
