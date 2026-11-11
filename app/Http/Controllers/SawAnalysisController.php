<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use App\Models\SawCalculation;
use App\Services\SawCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SawAnalysisController extends Controller
{
    /**
     * Halaman utama SAW Analysis.
     */
    public function index(Request $request)
    {
        $batches = RecruitmentBatch::orderBy('created_at', 'desc')
            ->get();

        $selectedBatch = null;
        $calculations = collect();

        if ($request->filled('batch')) {
            $selectedBatch = RecruitmentBatch::findOrFail(
                $request->batch
            );

            $calculations = $selectedBatch->sawCalculations()
                ->with('calculator')
                ->withCount('results')
                ->orderByDesc('version')
                ->get();
        }

        return view('saw-analysis.index', compact(
            'batches',
            'selectedBatch',
            'calculations'
        ));
    }

    /**
     * Menjalankan perhitungan SAW.
     */
    public function calculate(
        RecruitmentBatch $recruitmentBatch,
        SawCalculationService $sawService
    ) {
        $calculation = $sawService->calculate(
            $recruitmentBatch,
            Auth::id()
        );

        return redirect()
            ->route('saw-analysis.show', $calculation)
            ->with(
                'success',
                'Perhitungan SAW versi ' . $calculation->version
                . ' berhasil disimpan.'
            );
    }

    /**
     * Menampilkan detail hasil perhitungan.
     */
    public function show(SawCalculation $sawCalculation)
    {
        $sawCalculation->load([
            'recruitmentBatch',
            'calculator',
            'results.application.candidate',
            'details',
        ]);

        return view('saw-analysis.show', compact(
            'sawCalculation'
        ));
    }
}
