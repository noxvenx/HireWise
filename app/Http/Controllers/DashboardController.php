<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentBatch;
use App\Models\Candidate;
use App\Models\RecruitmentApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'active_batches' => RecruitmentBatch::where('status', 'active')->count(),
            'total_candidates' => Candidate::count(),
            'total_applications' => RecruitmentApplication::count(),
            'pending_screening' => RecruitmentApplication::where('screening_status', 'pending')->count(),
            'passed_screening' => RecruitmentApplication::where('screening_status', 'passed')->count(),
            'failed_screening' => RecruitmentApplication::where('screening_status', 'failed')->count(),
        ];

        $recentBatches = RecruitmentBatch::withCount('applications')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'statistics',
            'recentBatches'
        ));
    }
}
