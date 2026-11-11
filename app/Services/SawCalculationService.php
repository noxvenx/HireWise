<?php

namespace App\Services;

use App\Models\RecruitmentBatch;
use App\Models\SawCalculation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SawCalculationService
{
    public function calculate(RecruitmentBatch $batch, int $userId): SawCalculation
    {
        return DB::transaction(function () use ($batch, $userId) {

            // Kunci batch untuk mencegah bentrok nomor versi
            $batch = RecruitmentBatch::whereKey($batch->id)
                ->lockForUpdate()
                ->firstOrFail();

            $criteria = $batch->criteria()
                ->where('is_active', true)
                ->orderBy('criterion_code')
                ->get();

            if ($criteria->isEmpty()) {
                throw ValidationException::withMessages([
                    'criteria' => 'Belum ada kriteria aktif pada batch ini.',
                ]);
            }

            $totalWeight = (float) $criteria->sum('weight');

            if (abs($totalWeight - 100) > 0.01) {
                throw ValidationException::withMessages([
                    'weight' => 'Total bobot kriteria harus tepat 100%. Total saat ini: '
                        . number_format($totalWeight, 2) . '%.',
                ]);
            }

            $applications = $batch->applications()
                ->where('screening_status', 'passed')
                ->with(['candidate'])
                ->orderBy('id')
                ->get();

            if ($applications->isEmpty()) {
                throw ValidationException::withMessages([
                    'applications' => 'Belum ada kandidat yang lolos screening.',
                ]);
            }

            $criterionIds = $criteria->pluck('id');

            $assessments = \App\Models\CandidateAssessment::query()
                ->whereIn('recruitment_application_id', $applications->pluck('id'))
                ->whereIn('recruitment_criterion_id', $criterionIds)
                ->get()
                ->groupBy('recruitment_application_id');

            $matrix = [];

            foreach ($applications as $application) {
                $applicationAssessments = $assessments->get($application->id, collect());

                foreach ($criteria as $criterion) {
                    $assessment = $applicationAssessments->firstWhere(
                        'recruitment_criterion_id',
                        $criterion->id
                    );

                    if (!$assessment) {
                        throw ValidationException::withMessages([
                            'assessment' => 'Nilai kandidat '
                                . $application->candidate->full_name
                                . ' belum lengkap pada kriteria '
                                . $criterion->name . '.',
                        ]);
                    }

                    $score = (float) $assessment->score;

                    if ($score < 0) {
                        throw ValidationException::withMessages([
                            'assessment' => 'Nilai tidak boleh negatif.',
                        ]);
                    }

                    if ($criterion->attribute === 'cost' && $score <= 0) {
                        throw ValidationException::withMessages([
                            'assessment' => 'Nilai kriteria cost harus lebih besar dari 0.',
                        ]);
                    }

                    $matrix[$application->id][$criterion->id] = $score;
                }
            }

            // Cari nilai maksimum dan minimum setiap kriteria
            $extremes = [];

            foreach ($criteria as $criterion) {
                $values = [];

                foreach ($applications as $application) {
                    $values[] = $matrix[$application->id][$criterion->id];
                }

                $extremes[$criterion->id] = [
                    'max' => max($values),
                    'min' => min($values),
                ];

                if ($criterion->attribute === 'benefit'
                    && $extremes[$criterion->id]['max'] <= 0) {
                    throw ValidationException::withMessages([
                        'assessment' => 'Nilai maksimum kriteria benefit harus lebih besar dari 0.',
                    ]);
                }
            }

            // Hitung normalisasi dan nilai preferensi
            $calculatedResults = [];

            foreach ($applications as $application) {
                $preference = 0;

                foreach ($criteria as $criterion) {
                    $rawScore = $matrix[$application->id][$criterion->id];

                    if ($criterion->attribute === 'benefit') {
                        $normalized = $rawScore / $extremes[$criterion->id]['max'];
                    } else {
                        $normalized = $extremes[$criterion->id]['min'] / $rawScore;
                    }

                    $weighted = ((float) $criterion->weight / 100) * $normalized;

                    $preference += $weighted;

                    $calculatedResults[$application->id]['details'][] = [
                        'recruitment_application_id' => $application->id,
                        'recruitment_criterion_id' => $criterion->id,
                        'criterion_code' => $criterion->criterion_code,
                        'criterion_name' => $criterion->name,
                        'attribute' => $criterion->attribute,
                        'weight' => $criterion->weight,
                        'raw_score' => $rawScore,
                        'normalized_score' => round($normalized, 8),
                        'weighted_score' => round($weighted, 8),
                    ];
                }

                $calculatedResults[$application->id]['preference_score'] =
                    round($preference, 8);
            }

            // Urutkan nilai tertinggi ke terendah
            uasort($calculatedResults, function ($a, $b) {
                return $b['preference_score'] <=> $a['preference_score'];
            });

            // Nomor peringkat menggunakan competition ranking:
            // 1, 1, 3 apabila terdapat nilai seri.
            $previousScore = null;
            $previousRank = null;
            $position = 0;

            foreach ($calculatedResults as &$result) {
                $position++;

                $currentScore = $result['preference_score'];

                if ($previousScore !== null
                    && $currentScore === $previousScore) {
                    $result['rank'] = $previousRank;
                } else {
                    $result['rank'] = $position;
                    $previousRank = $position;
                }

                $previousScore = $currentScore;
            }

            unset($result);

            // Buat versi perhitungan baru
            $version = ((int) $batch->sawCalculations()->max('version')) + 1;

            $calculation = SawCalculation::create([
                'recruitment_batch_id' => $batch->id,
                'version' => $version,
                'calculated_by' => $userId,
                'calculated_at' => now(),
                'total_weight' => $totalWeight,
                'candidate_count' => $applications->count(),
            ]);

            // Simpan hasil dan detail perhitungan
            foreach ($calculatedResults as $applicationId => $result) {
                $calculation->results()->create([
                    'recruitment_application_id' => $applicationId,
                    'preference_score' => $result['preference_score'],
                    'rank' => $result['rank'],
                ]);

                foreach ($result['details'] as $detail) {
                    $calculation->details()->create($detail);
                }
            }

            return $calculation;
        });
    }
}
