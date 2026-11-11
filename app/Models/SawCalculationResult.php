<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SawCalculationResult extends Model
{
    protected $fillable = [
        'saw_calculation_id',
        'recruitment_application_id',
        'preference_score',
        'rank',
    ];

    protected function casts(): array
    {
        return [
            'preference_score' => 'decimal:8',
            'rank' => 'integer',
        ];
    }

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(SawCalculation::class, 'saw_calculation_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(RecruitmentApplication::class, 'recruitment_application_id');
    }
}
