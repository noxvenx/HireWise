<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAssessment extends Model
{
    protected $fillable = [
        'recruitment_application_id',
        'recruitment_criterion_id',
        'score',
        'assessed_by',
        'assessed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'assessed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            RecruitmentApplication::class,
            'recruitment_application_id'
        );
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(
            RecruitmentCriterion::class,
            'recruitment_criterion_id'
        );
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
