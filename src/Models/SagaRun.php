<?php

namespace dayemsiddiqui\Saga\Models;

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
}