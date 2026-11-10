<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Support\Facades\DB;

class CandidateCodeGenerator
{
    public function generate(): string
    {
        return DB::transaction(function () {
            $lastCandidate = Candidate::query()
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $nextNumber = ($lastCandidate?->id ?? 0) + 1;

            return 'HW-CND-' . str_pad(
                $nextNumber,
                6,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}
