<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Services\CandidateCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $candidates = Candidate::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('candidate_code', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('candidates.index', compact('candidates', 'search'));
    }

    public function create()
    {
        return view('candidates.create');
    }

    public function store(
        Request $request,
        CandidateCodeGenerator $codeGenerator
    ) {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'education' => ['nullable', 'string', 'max:100'],
            'institution' => ['nullable', 'string', 'max:150'],
            'major' => ['nullable', 'string', 'max:150'],
            'graduation_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 5)],
            'notes' => ['nullable', 'string'],
        ]);

        $candidate = DB::transaction(function () use (
            $validated,
            $codeGenerator
        ) {
            $validated['candidate_code'] = $codeGenerator->generate();

            return Candidate::create($validated);
        });

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'Data kandidat berhasil ditambahkan.');
    }

    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'education' => ['nullable', 'string', 'max:100'],
            'institution' => ['nullable', 'string', 'max:150'],
            'major' => ['nullable', 'string', 'max:150'],
            'graduation_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 5)],
            'notes' => ['nullable', 'string'],
        ]);

        $candidate->update($validated);

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('success', 'Data kandidat berhasil diperbarui.');
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->applications()->exists()) {
            return redirect()
                ->route('candidates.index')
                ->with('error', 'Kandidat sudah memiliki riwayat lamaran dan tidak dapat dihapus.');
        }

        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Data kandidat berhasil dihapus.');
    }
}
