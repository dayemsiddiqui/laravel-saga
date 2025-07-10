<?php

namespace dayemsiddiqui\Saga\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function currentStep(): SagaStep|null
    {
        return $this->steps()->where('status', 'pending')->first();
    }

    public function doneSteps(): HasMany
    {
        return $this->steps()->whereIn('status', ['completed', 'failed']);
    }

    public function failedSteps(): HasMany
    {
        return $this->steps()->where('status', 'failed');
    }

    public function pendingSteps(): HasMany
    {
        return $this->steps()->where('status', 'pending');
    }
}
