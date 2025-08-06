<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CostModelController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route (optional, redirect to cost-model)
Route::get('/dashboard', function () {
    return redirect('/cost-model');
})->name('dashboard')->middleware('auth');

// Redirect root to cost-model if authenticated, otherwise to login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/cost-model');
    }
    return redirect('/login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Cost Model Calculator Routes
    Route::get('/cost-model', [CostModelController::class, 'index'])->name('cost-model.index');

    // Master Nomor Polisi Routes
    Route::get('/police-units', function () {
        return view('police-units');
    })->name('police-units.index');

    // Test API Route
    Route::get('/test-api', function () {
        return view('test-api');
    });
});

// API Routes untuk Cost Model (protected)
Route::prefix('api/cost-model')->middleware(['auth'])->group(function () {
    // API untuk upsert semua data dalam satu request
    Route::post('/upsert-all', [CostModelController::class, 'upsertAllData'])->name('api.cost-model.upsert-all');
    Route::get('/stored-data', [CostModelController::class, 'getStoredData'])->name('api.cost-model.stored-data');
    
    // API untuk monitoring data
    Route::post('/upsert-monitoring', [CostModelController::class, 'upsertMonitoringData'])->name('api.cost-model.upsert-monitoring');
    Route::post('/upsert-existing-monitoring', [CostModelController::class, 'upsertExistingMonitoringData'])->name('api.cost-model.upsert-existing-monitoring');
    Route::get('/monitoring-data', [CostModelController::class, 'getMonitoringData'])->name('api.cost-model.monitoring-data');
    Route::get('/latest-monitoring-data', [CostModelController::class, 'getLatestMonitoringData'])->name('api.cost-model.latest-monitoring-data');
    Route::get('/all-unit-police-numbers', [CostModelController::class, 'getAllUnitPoliceNumbers'])->name('api.cost-model.all-unit-police-numbers');
    
    // API untuk master nomor polisi
    Route::get('/police-units', [CostModelController::class, 'getAllPoliceUnits'])->name('api.cost-model.police-units');
    Route::post('/police-units', [CostModelController::class, 'savePoliceUnit'])->name('api.cost-model.save-police-unit');
    Route::delete('/police-units', [CostModelController::class, 'deletePoliceUnit'])->name('api.cost-model.delete-police-unit');
    
    // API untuk perhitungan
    Route::post('/calculate', [CostModelController::class, 'calculate'])->name('api.cost-model.calculate');
    
    // API untuk dashboard data
    Route::get('/dashboard-data', [CostModelController::class, 'getDashboardData'])->name('api.cost-model.dashboard-data');
    
    // API untuk monitoring notes
    Route::post('/save-monitoring-note', [CostModelController::class, 'saveMonitoringNote'])->name('api.cost-model.save-monitoring-note');
    Route::get('/get-monitoring-note', [CostModelController::class, 'getMonitoringNote'])->name('api.cost-model.get-monitoring-note');
});
