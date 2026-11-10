<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecruitmentBatchController extends Controller
{
    public function index()
    {
        $batches = RecruitmentBatch::with('creator')
            ->latest()
            ->paginate(10);

        return view('recruitment-batches.index', compact('batches'));
    }

    public function create()
    {
        return view('recruitment-batches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_code' => ['required', 'string', 'max:30', 'unique:recruitment_batches,batch_code'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['draft', 'active', 'closed', 'completed'])],
        ]);

        $validated['created_by'] = auth()->id();

        RecruitmentBatch::create($validated);

        return redirect()
            ->route('recruitment-batches.index')
            ->with('success', 'Recruitment Batch berhasil dibuat.');
    }

   
    public function show(RecruitmentBatch $recruitmentBatch)
    {
        $recruitmentBatch->load([
            'applications.candidate',
            'applications.creator',
        ]);

        $availableCandidates = \App\Models\Candidate::query()
            ->whereDoesntHave('applications', function ($query) use ($recruitmentBatch) {
                $query->where('recruitment_batch_id', $recruitmentBatch->id);
            })
            ->orderBy('full_name')
            ->get();

        return view('recruitment-batches.show', compact(
            'recruitmentBatch',
            'availableCandidates'
        ));
    }


    public function edit(RecruitmentBatch $recruitmentBatch)
    {
        return view('recruitment-batches.edit', compact('recruitmentBatch'));
    }

    public function update(Request $request, RecruitmentBatch $recruitmentBatch)
    {
        $validated = $request->validate([
            'batch_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('recruitment_batches', 'batch_code')->ignore($recruitmentBatch->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['draft', 'active', 'closed', 'completed'])],
        ]);

        $recruitmentBatch->update($validated);

        return redirect()
            ->route('recruitment-batches.index')
            ->with('success', 'Recruitment Batch berhasil diperbarui.');
    }

    public function destroy(RecruitmentBatch $recruitmentBatch)
    {
        $recruitmentBatch->delete();

        return redirect()
            ->route('recruitment-batches.index')
            ->with('success', 'Recruitment Batch berhasil dihapus.');
    }
}