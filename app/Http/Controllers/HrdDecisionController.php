<?php

namespace App\Http\Controllers;

use App\Models\HrdDecision;
use App\Models\RecruitmentApplication;
use App\Models\RecruitmentBatch;
use App\Models\SawCalculation;
use App\Services\SystemRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HrdDecisionController extends Controller
{
    public function index(Request $request)
    {
        $batches = RecruitmentBatch::orderBy('created_at', 'desc')->get();

        $selectedBatch = null;
        $calculations = collect();
        $selectedCalculation = null;
        $latestDecisions = collect();
        $systemRecommendations = collect();

        $batchId = $request->input('batch_id');

        if ($batchId) {
            $selectedBatch = RecruitmentBatch::find($batchId);
        } elseif ($batches->isNotEmpty()) {
            $selectedBatch = $batches->first();
        }

        if ($selectedBatch) {
            $calculations = SawCalculation::where(
                'recruitment_batch_id',
                $selectedBatch->id
            )->orderByDesc('version')->get();

            $calculationId = $request->input('calculation_id');

            if ($calculationId) {
                $selectedCalculation = $calculations->firstWhere(
                    'id',
                    (int) $calculationId
                );
            }

            if (!$selectedCalculation) {
                $selectedCalculation = $calculations->first();
            }
        }

        if ($selectedCalculation) {
        $selectedCalculation->load(['results.application.candidate']);

        $applicationIds = $selectedCalculation->results->pluck('recruitment_application_id');

        $latestDecisions = HrdDecision::where('saw_calculation_id', $selectedCalculation->id)
            ->whereIn('recruitment_application_id', $applicationIds)
            ->with('decider')
            ->orderByDesc('id')
            ->get()
            ->groupBy('recruitment_application_id')
            ->map(fn ($decisions) => $decisions->first());

        $recommendationService = new SystemRecommendationService();

        $systemRecommendations = $selectedCalculation->results
            ->mapWithKeys(function ($result) use ($selectedBatch, $recommendationService) {
                $recommendation = $recommendationService->determine(
                    (int) $result->rank,
                    (int) $selectedBatch->quota,
                    (int) $selectedBatch->reserve_quota
                );

                return [$result->recruitment_application_id => $recommendation];
            });
    }

            return view('hrd-decisions.index', compact(
                'batches',
                'selectedBatch',
                'calculations',
                'selectedCalculation',
                'latestDecisions',
                'systemRecommendations'
            ));
        }
    
    public function store(
        Request $request,
        SawCalculation $sawCalculation,
        RecruitmentApplication $application
    ) {
        $validated = $request->validate([
            'decision_status' => [
                'required',
                'in:recommended,reserve,accepted,rejected,pending_decision',
            ],
            'reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if (
            $sawCalculation->recruitment_batch_id !==
            $application->recruitment_batch_id
        ) {
            abort(404);
        }

        $isInCalculation = $sawCalculation->results()
            ->where('recruitment_application_id', $application->id)
            ->exists();

        if (!$isInCalculation) {
            abort(404);
        }

        $latestDecision = HrdDecision::where(
            'saw_calculation_id',
            $sawCalculation->id
        )
            ->where('recruitment_application_id', $application->id)
            ->latest('id')
            ->first();

        $reason = $validated['reason'] ?? null;

        // Hindari membuat riwayat baru jika tidak ada perubahan.
        if (
            $latestDecision &&
            $latestDecision->decision_status === $validated['decision_status'] &&
            $latestDecision->reason === $reason
        ) {
            return redirect()
                ->route('hrd-decisions.index', [
                    'batch_id' => $sawCalculation->recruitment_batch_id,
                    'calculation_id' => $sawCalculation->id,
                ])
                ->with('success', 'Tidak ada perubahan keputusan.');
        }

        DB::transaction(function () use (
            $sawCalculation,
            $application,
            $validated,
            $reason
        ) {
            HrdDecision::create([
                'recruitment_application_id' => $application->id,
                'saw_calculation_id' => $sawCalculation->id,
                'decision_status' => $validated['decision_status'],
                'reason' => $reason,
                'decided_by' => Auth::id(),
                'decided_at' => now(),
            ]);
        });

        return redirect()
            ->route('hrd-decisions.index', [
                'batch_id' => $sawCalculation->recruitment_batch_id,
                'calculation_id' => $sawCalculation->id,
            ])
            ->with('success', 'Keputusan HRD berhasil disimpan.');
    }
}
