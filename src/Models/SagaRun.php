<?php

namespace dayemsiddiqui\Saga\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $status
 * @property array $context
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \dayemsiddiqui\Saga\Models\SagaStep> $steps
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SagaRun extends Model
{
    protected $fillable = ['name', 'status', 'context'];

    protected $casts = [
        'context' => 'array',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(SagaStep::class);
    }

    public function currentStep(): ?SagaStep
    {
        $step = $this->steps()->where('status', SagaStepStatus::PENDING)->first();

        return $step ? new SagaStep($step->toArray()) : null;
    }

    public function doneSteps(): HasMany
    {
        return $this->steps()->whereIn('status', [SagaStepStatus::COMPLETED, SagaStepStatus::FAILED]);
    }

    public function failedSteps(): HasMany
    {
        return $this->steps()->where('status', SagaStepStatus::FAILED);
    }

    public function pendingSteps(): HasMany
    {
        return $this->steps()->where('status', SagaStepStatus::PENDING);
    }
}
