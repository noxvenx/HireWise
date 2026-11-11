<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use App\Models\RecruitmentCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RecruitmentCriterionController extends Controller
{
    /**
     * Main Criteria Management Hub.
     */
    public function hub(Request $request)
    {
        $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:recruitment_batches,id'],
        ]);

        $batches = RecruitmentBatch::withCount('criteria')
            ->orderByDesc('created_at')
            ->get();

        $recruitmentBatch = $request->filled('batch_id')
            ? $batches->firstWhere('id', $request->batch_id)
            : null;

        $criteria = $recruitmentBatch
            ? $recruitmentBatch->criteria()
                ->orderBy('criterion_code')
                ->get()
            : collect();

        // Total bobot hanya menghitung kriteria aktif.
        $totalWeight = (float) $criteria
            ->where('is_active', true)
            ->sum('weight');

        $isWeightValid = abs($totalWeight - 100) < 0.001;

        return view('criteria.index', compact(
            'batches',
            'recruitmentBatch',
            'criteria',
            'totalWeight',
            'isWeightValid'
        ));
    }

    /**
     * Legacy route compatibility.
     */
    public function index(RecruitmentBatch $recruitmentBatch)
    {
        return redirect()->route('criteria-management.index', [
            'batch_id' => $recruitmentBatch->id,
        ]);
    }

    /**
     * Store new criterion.
     */
    public function store(
        Request $request,
        RecruitmentBatch $recruitmentBatch
    ) {
        $validated = $request->validate([
            'criterion_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('recruitment_criteria', 'criterion_code')
                    ->where(fn ($query) => $query->where(
                        'recruitment_batch_id',
                        $recruitmentBatch->id
                    )),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'attribute' => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => ['required', 'numeric', 'gt:0', 'lte:100'],
        ]);

        DB::transaction(function () use (
            $recruitmentBatch,
            $validated
        ) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureWeightsUnlocked($batch);

            $currentTotal = (float) $batch->criteria()
                ->where('is_active', true)
                ->sum('weight');

            if (($currentTotal + (float) $validated['weight']) > 100.001) {
                throw ValidationException::withMessages([
                    'weight' => 'Total bobot kriteria tidak boleh melebihi 100%.',
                ]);
            }

            $batch->criteria()->create([
                'criterion_code' => strtoupper($validated['criterion_code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'attribute' => $validated['attribute'],
                'weight' => $validated['weight'],
                'is_active' => true,
            ]);
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Kriteria berhasil ditambahkan.'
        );
    }

    /**
     * Update criterion.
     */
    public function update(
        Request $request,
        RecruitmentBatch $recruitmentBatch,
        RecruitmentCriterion $criterion
    ) {
        DB::transaction(function () use (
            $request,
            $recruitmentBatch,
            $criterion
        ) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentCriterion = $batch->criteria()
                ->whereKey($criterion->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Ketika terkunci, hanya nama dan deskripsi yang boleh diubah.
            if ($batch->criteria_weights_locked) {
                $validated = $request->validate([
                    'name' => ['required', 'string', 'max:150'],
                    'description' => ['nullable', 'string'],
                ]);

                $currentCriterion->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ]);

                return;
            }

            $validated = $request->validate([
                'criterion_code' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('recruitment_criteria', 'criterion_code')
                        ->where(fn ($query) => $query->where(
                            'recruitment_batch_id',
                            $batch->id
                        ))
                        ->ignore($currentCriterion->id),
                ],
                'name' => ['required', 'string', 'max:150'],
                'description' => ['nullable', 'string'],
                'attribute' => ['required', Rule::in(['benefit', 'cost'])],
                'weight' => ['required', 'numeric', 'gt:0', 'lte:100'],
            ]);

            $otherActiveWeight = (float) $batch->criteria()
                ->where('is_active', true)
                ->where('id', '!=', $currentCriterion->id)
                ->sum('weight');

            $newTotal = $otherActiveWeight;

            if ($currentCriterion->is_active) {
                $newTotal += (float) $validated['weight'];
            }

            if ($newTotal > 100.001) {
                throw ValidationException::withMessages([
                    'weight' => 'Total bobot kriteria tidak boleh melebihi 100%.',
                ]);
            }

            $currentCriterion->update([
                'criterion_code' => strtoupper($validated['criterion_code']),
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'attribute' => $validated['attribute'],
                'weight' => $validated['weight'],
            ]);
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Kriteria berhasil diperbarui.'
        );
    }

    /**
     * Delete criterion.
     */
    public function destroy(
        RecruitmentBatch $recruitmentBatch,
        RecruitmentCriterion $criterion
    ) {
        DB::transaction(function () use (
            $recruitmentBatch,
            $criterion
        ) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureWeightsUnlocked($batch);

            $currentCriterion = $batch->criteria()
                ->whereKey($criterion->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Lindungi data assessment dan riwayat perhitungan SAW.
            if (
                $currentCriterion->assessments()->exists()
                || $currentCriterion->sawDetails()->exists()
            ) {
                throw ValidationException::withMessages([
                    'criterion' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam assessment atau riwayat SAW.',
                ]);
            }

            $currentCriterion->delete();
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Kriteria berhasil dihapus.'
        );
    }

    /**
     * Redistribute active criteria weights evenly.
     */
    public function renormalize(RecruitmentBatch $recruitmentBatch)
    {
        DB::transaction(function () use ($recruitmentBatch) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->ensureWeightsUnlocked($batch);

            $criteria = $batch->criteria()
                ->where('is_active', true)
                ->orderBy('criterion_code')
                ->lockForUpdate()
                ->get();

            $count = $criteria->count();

            if ($count === 0) {
                throw ValidationException::withMessages([
                    'criteria' => 'Belum ada kriteria aktif untuk dinormalisasi.',
                ]);
            }

            // 100% = 10.000 basis points.
            // Pembagian dilakukan dalam integer agar total tepat 100.00%.
            $totalBasisPoints = 10000;
            $baseWeight = intdiv($totalBasisPoints, $count);
            $remainder = $totalBasisPoints % $count;

            foreach ($criteria as $index => $criterion) {
                $weight = $baseWeight + ($index < $remainder ? 1 : 0);

                $criterion->update([
                    'weight' => $weight / 100,
                ]);
            }
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Bobot kriteria aktif berhasil dinormalisasi menjadi 100%.'
        );
    }

    /**
     * Lock criterion weights.
     */
    public function lockWeights(RecruitmentBatch $recruitmentBatch)
    {
        DB::transaction(function () use ($recruitmentBatch) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($batch->criteria_weights_locked) {
                throw ValidationException::withMessages([
                    'criteria' => 'Konfigurasi bobot sudah terkunci.',
                ]);
            }

            $criteria = $batch->criteria()
                ->where('is_active', true)
                ->lockForUpdate()
                ->get();

            $totalWeight = (float) $criteria->sum('weight');

            if ($criteria->isEmpty()) {
                throw ValidationException::withMessages([
                    'criteria' => 'Tidak ada kriteria aktif untuk dikunci.',
                ]);
            }

            if (abs($totalWeight - 100) >= 0.001) {
                throw ValidationException::withMessages([
                    'weight' => 'Total bobot harus tepat 100% sebelum konfigurasi dapat dikunci. Total saat ini: '
                        . number_format($totalWeight, 2)
                        . '%.',
                ]);
            }

            $batch->update([
                'criteria_weights_locked' => true,
            ]);
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Konfigurasi bobot berhasil dikunci.'
        );
    }

    /**
     * Unlock criterion weights.
     */
    public function unlockWeights(RecruitmentBatch $recruitmentBatch)
    {
        DB::transaction(function () use ($recruitmentBatch) {
            $batch = RecruitmentBatch::whereKey($recruitmentBatch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $batch->update([
                'criteria_weights_locked' => false,
            ]);
        });

        return $this->hubRedirect(
            $recruitmentBatch,
            'Konfigurasi bobot berhasil dibuka kembali.'
        );
    }

    /**
     * Ensure weight configuration is unlocked.
     */
    private function ensureWeightsUnlocked(RecruitmentBatch $batch): void
    {
        if ($batch->criteria_weights_locked) {
            throw ValidationException::withMessages([
                'criteria' => 'Konfigurasi bobot sedang terkunci. Unlock terlebih dahulu untuk melakukan perubahan.',
            ]);
        }
    }

    /**
     * Redirect back to Criteria Management Hub.
     */
    private function hubRedirect(
        RecruitmentBatch $batch,
        string $message
    ) {
        return redirect()
            ->route('criteria-management.index', [
                'batch_id' => $batch->id,
            ])
            ->with('success', $message);
    }
}
