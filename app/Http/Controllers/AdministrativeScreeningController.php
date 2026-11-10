<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdministrativeScreeningController extends Controller
{
    public function index(Request $request)
    {
        $query = RecruitmentApplication::with([
            'candidate',
            'recruitmentBatch',
            'screener',
        ]);

        if ($request->filled('batch')) {
            $query->where('recruitment_batch_id', $request->batch);
        }

        if ($request->filled('status')) {
            $query->where('screening_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('candidate_code', 'like', "%{$search}%");
            });
        }

        $applications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $batches = \App\Models\RecruitmentBatch::orderBy('name')->get();

        return view('administrative-screening.index', compact(
            'applications',
            'batches'
        ));
    }

    public function update(Request $request, RecruitmentApplication $application)
    {
        $validated = $request->validate([
            'screening_status' => [
                'required',
                Rule::in(['pending', 'passed', 'failed']),
            ],
            'screening_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $application->update([
            'screening_status' => $validated['screening_status'],
            'screening_notes' => $validated['screening_notes'] ?? null,
            'screened_by' => auth()->id(),
            'screened_at' => now(),
        ]);

        return redirect()
            ->route('administrative-screening.index')
            ->with('success', 'Hasil administrative screening berhasil diperbarui.');
    }
}
