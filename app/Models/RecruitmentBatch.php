<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruitmentBatch extends Model
{
    protected $fillable = [
        'batch_code',
        'name',
        'position',
        'description',
        'quota',
        'reserve_quota',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'reserve_quota' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(RecruitmentApplication::class);
    }

    public function criteria(): HasMany
    {
    return $this->hasMany(RecruitmentCriterion::class);
    }

    public function sawCalculations(): HasMany
    {
    return $this->hasMany(SawCalculation::class);
    }
}