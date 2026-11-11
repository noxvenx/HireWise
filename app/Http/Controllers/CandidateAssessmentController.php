<?php

namespace App\Http\Controllers;

use App\Models\CandidateAssessment;
use App\Models\RecruitmentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CandidateAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $batches = RecruitmentBatch::orderBy('name')->get();

        $selectedBatch = null;
        $applications = collect();
        $criteria = collect();

        if ($request->filled('batch')) {
            $selectedBatch = RecruitmentBatch::findOrFail($request->batch);

            $criteria = $selectedBatch->criteria()
                ->where('is_active', true)
                ->orderBy('criterion_code')
                ->get();

            $applications = $selectedBatch->applications()
                ->with([
                    'candidate',
                    'assessments.criterion',
                ])
                ->where('screening_status', 'passed')
                ->latest()
                ->get();
        }

        return view('candidate-assessments.index', compact(
            'batches',
            'selectedBatch',
            'applications',
            'criteria'
        ));
    }

    public function store(Request $request, RecruitmentBatch $recruitmentBatch)
    {
        $validated = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $recruitmentBatch) {
            foreach ($validated['scores'] as $applicationId => $criterionScores) {
                $application = $recruitmentBatch->applications()
                    ->where('screening_status', 'passed')
                    ->findOrFail($applicationId);

                foreach ($criterionScores as $criterionId => $score) {
                    $criterion = $recruitmentBatch->criteria()
                        ->where('is_active', true)
                        ->findOrFail($criterionId);

                    // Nilai kosong tidak mengubah data yang sudah tersimpan.
                    if ($score === null || $score === '') {
                        continue;
                    }

                    // Kriteria cost tidak boleh bernilai nol.
                    if ($criterion->attribute === 'cost' && (float) $score <= 0) {
                        throw ValidationException::withMessages([
                            "scores.{$applicationId}.{$criterionId}" =>
                                "Nilai untuk kriteria cost {$criterion->name} harus lebih dari 0.",
                        ]);
                    }

                    CandidateAssessment::updateOrCreate(
                        [
                            'recruitment_application_id' => $application->id,
                            'recruitment_criterion_id' => $criterion->id,
                        ],
                        [
                            'score' => $score,
                            'assessed_by' => auth()->id(),
                            'assessed_at' => now(),
                        ]
                    );
                }
            }
        });

        return redirect()
            ->route('candidate-assessments.index', ['batch' => $recruitmentBatch->id])
            ->with('success', 'Penilaian kandidat berhasil disimpan.');
    }

}
