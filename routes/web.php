<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdministrativeScreeningController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\RecruitmentBatchController;
use App\Http\Controllers\RecruitmentApplicationController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::middleware('role:super_admin,admin_hrd')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
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
        Route::post(
            '/recruitment-batches/{recruitmentBatch}/applications',
            [RecruitmentApplicationController::class, 'store']
            )->name('recruitment-batches.applications.store');
        Route::get('/administrative-screening', [AdministrativeScreeningController::class, 'index'])
            ->name('administrative-screening.index');
        Route::patch('/administrative-screening/{application}', [AdministrativeScreeningController::class, 'update'])
            ->name('administrative-screening.update');
    });
});