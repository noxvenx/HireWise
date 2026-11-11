<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdministrativeScreeningController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateAssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HrdDecisionController;
use App\Http\Controllers\RecruitmentBatchController;
use App\Http\Controllers\RecruitmentApplicationController;
use App\Http\Controllers\RecruitmentCriterionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SawAnalysisController;



Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::middleware('role:super_admin,admin_hrd')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
    });
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserManagementController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
            ->names('users');
    });
    Route::middleware('role:super_admin,admin_hrd')->group(function () {
        Route::resource('recruitment-batches', RecruitmentBatchController::class)
            ->names('recruitment-batches');

        Route::resource('candidates', CandidateController::class)
            ->names('candidates');

        Route::get('/candidate-assessments', [CandidateAssessmentController::class, 'index'])
            ->name('candidate-assessments.index');
        
        Route::post('/candidate-assessments/{recruitmentBatch}', [CandidateAssessmentController::class, 'store'])
            ->name('candidate-assessments.store');

        Route::post(
            '/recruitment-batches/{recruitmentBatch}/applications',
            [RecruitmentApplicationController::class, 'store']
            )->name('recruitment-batches.applications.store');

        Route::get('/administrative-screening', [AdministrativeScreeningController::class, 'index'])
            ->name('administrative-screening.index');

        Route::patch('/administrative-screening/{application}', [AdministrativeScreeningController::class, 'update'])
            ->name('administrative-screening.update');

        // SAW Analysis
        Route::get('/saw-analysis', [SawAnalysisController::class, 'index'])
            ->name('saw-analysis.index');

        Route::post('/saw-analysis/{recruitmentBatch}/calculate', [SawAnalysisController::class, 'calculate'])
            ->name('saw-analysis.calculate');

        Route::get('/saw-analysis/calculations/{sawCalculation}', [SawAnalysisController::class, 'show'])
            ->name('saw-analysis.show');

        // HRD Decisions
        Route::get('/hrd-decisions', [HrdDecisionController::class, 'index'])
            ->name('hrd-decisions.index');

        Route::post('/hrd-decisions/{sawCalculation}/{application}', [HrdDecisionController::class, 'store'])
            ->name('hrd-decisions.store');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])
            ->name('reports.export-excel');

        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.export-pdf');

        // Criteria Management Hub
        Route::get('/criteria-management', [RecruitmentCriterionController::class, 'hub'])
            ->name('criteria-management.index');

        Route::post(
            '/recruitment-batches/{recruitmentBatch}/criteria',
            [RecruitmentCriterionController::class, 'store']
        )->name('recruitment-batches.criteria.store');

        Route::put(
            '/recruitment-batches/{recruitmentBatch}/criteria/{criterion}',
            [RecruitmentCriterionController::class, 'update']
        )->name('recruitment-batches.criteria.update');

        Route::delete(
            '/recruitment-batches/{recruitmentBatch}/criteria/{criterion}',
            [RecruitmentCriterionController::class, 'destroy']
        )->name('recruitment-batches.criteria.destroy');

        Route::post(
            '/recruitment-batches/{recruitmentBatch}/criteria/renormalize',
            [RecruitmentCriterionController::class, 'renormalize']
        )->name('recruitment-batches.criteria.renormalize');

        Route::post(
            '/recruitment-batches/{recruitmentBatch}/criteria/lock',
            [RecruitmentCriterionController::class, 'lockWeights']
        )->name('recruitment-batches.criteria.lock');

        Route::post(
            '/recruitment-batches/{recruitmentBatch}/criteria/unlock',
            [RecruitmentCriterionController::class, 'unlockWeights']
        )->name('recruitment-batches.criteria.unlock');

        // Legacy URL: keep old links from batch pages working and send them to the Hub.
        Route::get(
            '/recruitment-batches/{recruitmentBatch}/criteria',
            [RecruitmentCriterionController::class, 'index']
        )->name('recruitment-batches.criteria.index');
    });
});