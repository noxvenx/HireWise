<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SawCalculation extends Model
{
    protected $fillable = [
        'recruitment_batch_id',
        'version',
        'calculated_by',
        'calculated_at',
        'total_weight',
        'candidate_count',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'calculated_at' => 'datetime',
            'total_weight' => 'decimal:2',
            'candidate_count' => 'integer',
        ];
    }

    public function recruitmentBatch(): BelongsTo
    {
        return $this->belongsTo(RecruitmentBatch::class);
    }

    public function calculator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }

    public function results(): HasMany
    {
        return $this->hasMany(SawCalculationResult::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SawCalculationDetail::class);
    }
    public function hrdDecisions(): HasMany
    {
    return $this->hasMany(HrdDecision::class);
    }
}
