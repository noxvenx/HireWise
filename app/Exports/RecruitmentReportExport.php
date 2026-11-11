<?php

namespace App\Exports;

use App\Models\RecruitmentBatch;
use App\Models\SawCalculation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RecruitmentReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected RecruitmentBatch $batch;
    protected SawCalculation $calculation;
    protected array $screeningSummary;
    protected Collection $latestDecisions;
    protected Collection $systemRecommendations;

    public function __construct(
        RecruitmentBatch $batch,
        SawCalculation $calculation,
        array $screeningSummary,
        Collection $latestDecisions,
        Collection $systemRecommendations
    ) {
        $this->batch = $batch;
        $this->calculation = $calculation;
        $this->screeningSummary = $screeningSummary;
        $this->latestDecisions = $latestDecisions;
        $this->systemRecommendations = $systemRecommendations;
    }

    public function headings(): array
    {
        return [
            'Batch Code',
            'Batch Name',
            'Position',
            'Quota',
            'Reserve Quota',
            'Total Applicants',
            'Pending',
            'Passed',
            'Failed',
            'SAW Version',
            'Calculation Date',
            'Rank',
            'Application Code',
            'Candidate Code',
            'Candidate Name',
            'SAW Value',
            'System Recommendation',
            'HRD Decision',
            'Decision Reason',
            'Decision By',
            'Decision Date',
            'Criteria Details',
        ];
    }

    public function array(): array
    {
        $rows = [];

        $detailsByApplication = $this->calculation->details
            ->groupBy('recruitment_application_id');

        $results = $this->calculation->results
            ->sortBy('rank');

        foreach ($results as $result) {
            $applicationId = $result->recruitment_application_id;

            $application = $result->application;
            $candidate = $application?->candidate;

            $decision = $this->latestDecisions->get($applicationId);

            $recommendation = $this->systemRecommendations->get(
                $applicationId
            );

            $recommendationLabels = [
                'recommended' => 'Recommended',
                'reserve' => 'Reserve',
                'not_recommended' => 'Not Recommended',
            ];

            $decisionLabels = [
                'recommended' => 'Recommended',
                'reserve' => 'Reserve',
                'accepted' => 'Accepted',
                'rejected' => 'Rejected',
                'pending_decision' => 'Pending Decision',
            ];

            $criteriaDetails = $detailsByApplication
                ->get($applicationId, collect())
                ->map(function ($detail) {
                    return sprintf(
                        '%s - %s | Attribute: %s | Weight: %s | Raw: %s | Normalized: %s | Weighted: %s',
                        $detail->criterion_code,
                        $detail->criterion_name,
                        $detail->attribute,
                        $detail->weight,
                        $detail->raw_score,
                        $detail->normalized_score,
                        $detail->weighted_score
                    );
                })
                ->implode("\n");

            $rows[] = [
                $this->batch->batch_code,
                $this->batch->name,
                $this->batch->position,
                $this->batch->quota,
                $this->batch->reserve_quota,
                $this->screeningSummary['total'],
                $this->screeningSummary['pending'],
                $this->screeningSummary['passed'],
                $this->screeningSummary['failed'],
                $this->calculation->version,
                $this->calculation->calculated_at?->format('d-m-Y H:i:s'),
                $result->rank,
                $application?->application_code ?? '-',
                $candidate?->candidate_code ?? '-',
                $candidate?->full_name ?? '-',
                $result->preference_score,
                $recommendationLabels[$recommendation] ?? '-',
                $decisionLabels[$decision?->decision_status ?? ''] ?? 'Belum diputuskan',
                $decision?->reason ?? '-',
                $decision?->decider?->name ?? '-',
                $decision?->decided_at?->format('d-m-Y H:i:s') ?? '-',
                $criteriaDetails ?: '-',
            ];
        }

        return $rows;
    }
}
