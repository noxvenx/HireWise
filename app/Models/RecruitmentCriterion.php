<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentCriterion extends Model
{
    protected $table = 'recruitment_criteria';

    protected $fillable = [
        'recruitment_batch_id',
        'criterion_code',
        'name',
        'description',
        'attribute',
        'weight',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function recruitmentBatch(): BelongsTo
    {
        return $this->belongsTo(RecruitmentBatch::class);
    }

    public function assessments(): HasMany
    {
    return $this->hasMany(CandidateAssessment::class);
    }
    public function sawDetails(): HasMany
    {
    return $this->hasMany(SawCalculationDetail::class);
    }
}