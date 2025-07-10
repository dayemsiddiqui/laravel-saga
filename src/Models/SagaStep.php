<?php

namespace dayemsiddiqui\Saga\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SagaStep extends Model
{
    protected $fillable = ['saga_run_id', 'name', 'status', 'error_message'];

    protected $casts = [
        'status' => SagaStepStatus::class,
    ];

    public function sagaRun(): BelongsTo
    {
        return $this->belongsTo(SagaRun::class);
    }

    public function setStatus(SagaStepStatus $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === SagaStepStatus::PENDING;
    }

    public function isStarted(): bool
    {
        return $this->status === SagaStepStatus::STARTED;
    }

    public function isCompleted(): bool
    {
        return $this->status === SagaStepStatus::COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === SagaStepStatus::FAILED;
    }
}

enum SagaStepStatus: string
{
    case PENDING = 'pending';
    case STARTED = 'started';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
