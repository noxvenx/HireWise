<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use App\Models\RecruitmentCriterion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecruitmentCriterionController extends Controller
{
    public function index(RecruitmentBatch $recruitmentBatch)
    {
        $criteria = $recruitmentBatch->criteria()
            ->orderBy('criterion_code')
            ->get();

        $totalWeight = $criteria->sum('weight');

        return view('criteria.index', compact(
            'recruitmentBatch',
            'criteria',
            'totalWeight'
        ));
    }

    public function store(Request $request, RecruitmentBatch $recruitmentBatch)
    {
        $validated = $request->validate([
            'criterion_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('recruitment_criteria', 'criterion_code')
                    ->where('recruitment_batch_id', $recruitmentBatch->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'attribute' => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => ['required', 'numeric', 'gt:0', 'lte:100'],
        ]);

        $currentTotal = $recruitmentBatch->criteria()->sum('weight');

        if (($currentTotal + $validated['weight']) > 100) {
            return back()
                ->withInput()
                ->withErrors([
                    'weight' => 'Total bobot seluruh kriteria tidak boleh melebihi 100%.',
                ]);
        }

        $recruitmentBatch->criteria()->create([
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()
            ->route('recruitment-batches.criteria.index', $recruitmentBatch)
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function update(
        Request $request,
        RecruitmentBatch $recruitmentBatch,
        RecruitmentCriterion $criterion
    ) {
        $criterion = $recruitmentBatch->criteria()
            ->findOrFail($criterion->id);

        $validated = $request->validate([
            'criterion_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('recruitment_criteria', 'criterion_code')
                    ->where('recruitment_batch_id', $recruitmentBatch->id)
                    ->ignore($criterion->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'attribute' => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => ['required', 'numeric', 'gt:0', 'lte:100'],
        ]);

        $otherWeight = $recruitmentBatch->criteria()
            ->where('id', '!=', $criterion->id)
            ->sum('weight');

        if (($otherWeight + $validated['weight']) > 100) {
            return back()
                ->withInput()
                ->withErrors([
                    'weight' => 'Total bobot seluruh kriteria tidak boleh melebihi 100%.',
                ]);
        }

        $criterion->update($validated);

        return redirect()
            ->route('recruitment-batches.criteria.index', $recruitmentBatch)
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(
        RecruitmentBatch $recruitmentBatch,
        RecruitmentCriterion $criterion
    ) {
        $criterion = $recruitmentBatch->criteria()
            ->findOrFail($criterion->id);

        $criterion->delete();

        return redirect()
            ->route('recruitment-batches.criteria.index', $recruitmentBatch)
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
