<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrdDecision extends Model
{
    protected $fillable = [
        'recruitment_application_id',
        'saw_calculation_id',
        'decision_status',
        'reason',
        'decided_by',
        'decided_at',
    ];

    protected function casts(): array
    {
        return [
            'decided_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            RecruitmentApplication::class,
            'recruitment_application_id'
        );
    }

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(
            SawCalculation::class,
            'saw_calculation_id'
        );
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
