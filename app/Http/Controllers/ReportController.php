<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use App\Models\SawCalculation;
use App\Models\HrdDecision;
use App\Services\SystemRecommendationService;
use App\Exports\RecruitmentReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(
        Request $request,
        SystemRecommendationService $recommendationService
    ) {
        $batches = RecruitmentBatch::orderByDesc('created_at')->get();

        $selectedBatch = null;
        $calculations = collect();
        $selectedCalculation = null;
        $latestDecisions = collect();
        $systemRecommendations = collect();

        $screeningSummary = [
            'total' => 0,
            'pending' => 0,
            'passed' => 0,
            'failed' => 0,
        ];

        if ($batches->isNotEmpty()) {
            $batchId = $request->input('batch_id');

            $selectedBatch = $batches->firstWhere('id', $batchId)
                ?? $batches->first();

            $screeningSummary['total'] = $selectedBatch
                ->applications()
                ->count();

            $screeningSummary['pending'] = $selectedBatch
                ->applications()
                ->where('screening_status', 'pending')
                ->count();

            $screeningSummary['passed'] = $selectedBatch
                ->applications()
                ->where('screening_status', 'passed')
                ->count();

            $screeningSummary['failed'] = $selectedBatch
                ->applications()
                ->where('screening_status', 'failed')
                ->count();

            $calculations = SawCalculation::where(
                'recruitment_batch_id',
                $selectedBatch->id
            )
                ->orderByDesc('version')
                ->get();

            $calculationId = $request->input('calculation_id');

            $selectedCalculation = $calculations->firstWhere(
                'id',
                $calculationId
            ) ?? $calculations->first();

            if ($selectedCalculation) {
                $selectedCalculation->load([
                    'results.application.candidate',
                    'details',
                ]);

                $applicationIds = $selectedCalculation->results
                    ->pluck('recruitment_application_id');

                $latestDecisions = HrdDecision::where(
                    'saw_calculation_id',
                    $selectedCalculation->id
                )
                    ->whereIn('recruitment_application_id', $applicationIds)
                    ->with('decider')
                    ->orderByDesc('id')
                    ->get()
                    ->groupBy('recruitment_application_id')
                    ->map(fn ($decisions) => $decisions->first());

                foreach ($selectedCalculation->results as $result) {
                    $systemRecommendations->put(
                        $result->recruitment_application_id,
                        $recommendationService->determine(
                            $result->rank,
                            $selectedBatch->quota,
                            $selectedBatch->reserve_quota
                        )
                    );
                }
            }
        }

        return view('reports.index', compact(
            'batches',
            'selectedBatch',
            'calculations',
            'selectedCalculation',
            'screeningSummary',
            'latestDecisions',
            'systemRecommendations'
        ));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'batch_id' => ['required', 'exists:recruitment_batches,id'],
            'calculation_id' => ['required', 'exists:saw_calculations,id'],
        ]);

        $batch = RecruitmentBatch::findOrFail($request->batch_id);

        $calculation = SawCalculation::where(
            'recruitment_batch_id',
            $batch->id
        )->findOrFail($request->calculation_id);

        $calculation->load([
            'results.application.candidate',
            'details',
        ]);

        $applicationIds = $calculation->results
            ->pluck('recruitment_application_id');

        $latestDecisions = HrdDecision::where(
            'saw_calculation_id',
            $calculation->id
        )
            ->whereIn('recruitment_application_id', $applicationIds)
            ->with('decider')
            ->orderByDesc('id')
            ->get()
            ->groupBy('recruitment_application_id')
            ->map(fn ($decisions) => $decisions->first());

        $screeningSummary = [
            'total' => $batch->applications()->count(),
            'pending' => $batch->applications()->where('screening_status', 'pending')->count(),
            'passed' => $batch->applications()->where('screening_status', 'passed')->count(),
            'failed' => $batch->applications()->where('screening_status', 'failed')->count(),
        ];

        $recommendationService = app(
            \App\Services\SystemRecommendationService::class
        );

        $systemRecommendations = collect();

        foreach ($calculation->results as $result) {
            $systemRecommendations->put(
                $result->recruitment_application_id,
                $recommendationService->determine(
                    $result->rank,
                    $batch->quota,
                    $batch->reserve_quota
                )
            );
        }

        $pdf = Pdf::loadView('reports.pdf', compact(
            'batch',
            'calculation',
            'screeningSummary',
            'latestDecisions',
            'systemRecommendations'
        ))->setPaper('a4', 'landscape');

        return $pdf->download(
            'Recruitment-Report-'.$batch->batch_code.'-V'.$calculation->version.'.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
    $request->validate([
        'batch_id' => ['required', 'exists:recruitment_batches,id'],
        'calculation_id' => ['required', 'exists:saw_calculations,id'],
    ]);

    $batch = RecruitmentBatch::findOrFail($request->batch_id);

    $calculation = SawCalculation::where(
        'recruitment_batch_id',
        $batch->id
    )->findOrFail($request->calculation_id);

    $calculation->load([
        'results.application.candidate',
        'details',
    ]);

    $applicationIds = $calculation->results
        ->pluck('recruitment_application_id');

    $latestDecisions = HrdDecision::where(
        'saw_calculation_id',
        $calculation->id
    )
        ->whereIn('recruitment_application_id', $applicationIds)
        ->with('decider')
        ->orderByDesc('id')
        ->get()
        ->groupBy('recruitment_application_id')
        ->map(fn ($decisions) => $decisions->first());

    $screeningSummary = [
        'total' => $batch->applications()->count(),
        'pending' => $batch->applications()->where('screening_status', 'pending')->count(),
        'passed' => $batch->applications()->where('screening_status', 'passed')->count(),
        'failed' => $batch->applications()->where('screening_status', 'failed')->count(),
    ];

    $recommendationService = app(
        \App\Services\SystemRecommendationService::class
    );

    $systemRecommendations = collect();

    foreach ($calculation->results as $result) {
        $systemRecommendations->put(
            $result->recruitment_application_id,
            $recommendationService->determine(
                $result->rank,
                $batch->quota,
                $batch->reserve_quota
            )
        );
    }

    $export = new RecruitmentReportExport(
        $batch,
        $calculation,
        $screeningSummary,
        $latestDecisions,
        $systemRecommendations
    );

    return Excel::download(
        $export,
        'Recruitment-Report-'.$batch->batch_code.'-V'.$calculation->version.'.xlsx'
    );
    }
}
