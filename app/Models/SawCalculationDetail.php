<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SawCalculationDetail extends Model
{
    protected $fillable = [
        'saw_calculation_id',
        'recruitment_application_id',
        'recruitment_criterion_id',
        'criterion_code',
        'criterion_name',
        'attribute',
        'weight',
        'raw_score',
        'normalized_score',
        'weighted_score',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'raw_score' => 'decimal:2',
            'normalized_score' => 'decimal:8',
            'weighted_score' => 'decimal:8',
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

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCriterion::class, 'recruitment_criterion_id');
    }
}
