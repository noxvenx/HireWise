<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentApplication extends Model
{
    protected $fillable = [
        'application_code',
        'candidate_id',
        'recruitment_batch_id',
        'applied_at',
        'source',
        'notes',
        'created_by',
        'screening_status',
        'screened_by',
        'screened_at',
        'screening_notes',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
            'screened_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function recruitmentBatch(): BelongsTo
    {
        return $this->belongsTo(RecruitmentBatch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function screener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'screened_by');
    }
}