<?php

namespace dayemsiddiqui\Saga\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SagaStep extends Model
{
    protected $fillable = ['saga_run_id', 'name', 'status', 'error_message'];

    public function sagaRun(): BelongsTo
    {
        return $this->belongsTo(SagaRun::class);
    }

}