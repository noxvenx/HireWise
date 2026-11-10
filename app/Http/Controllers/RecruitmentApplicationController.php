<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RecruitmentApplicationController extends Controller
{
    public function store(Request $request, RecruitmentBatch $recruitmentBatch)
    {
        $validated = $request->validate([
            'candidate_id' => [
                'required',
                'exists:candidates,id',
                Rule::unique('recruitment_applications', 'candidate_id')
                    ->where('recruitment_batch_id', $recruitmentBatch->id),
            ],
            'source' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ], [
            'candidate_id.unique' => 'Kandidat ini sudah terdaftar di batch tersebut.',
        ]);

        DB::transaction(function () use ($validated, $recruitmentBatch) {
            $application = RecruitmentApplication::create([
                'application_code' => 'TMP-' . Str::random(20),
                'candidate_id' => $validated['candidate_id'],
                'recruitment_batch_id' => $recruitmentBatch->id,
                'applied_at' => now(),
                'source' => $validated['source'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $application->update([
                'application_code' => 'HW-APP-' . str_pad(
                    $application->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
            ]);
        });

        return redirect()
            ->route('recruitment-batches.show', $recruitmentBatch)
            ->with('success', 'Kandidat berhasil ditambahkan ke batch rekrutmen.');
    }
}
