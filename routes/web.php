<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CostModelController;

Route::get('/', function () {
    return view('index');
});

// Cost Model Calculator Routes
Route::get('/cost-model', [CostModelController::class, 'index'])->name('cost-model.index');

// Test API Route
Route::get('/test-api', function () {
    return view('test-api');
});

// API Routes untuk Cost Model
Route::prefix('api/cost-model')->group(function () {
    // API untuk upsert semua data dalam satu request
    Route::post('/upsert-all', [CostModelController::class, 'upsertAllData'])->name('api.cost-model.upsert-all');
    Route::get('/stored-data', [CostModelController::class, 'getStoredData'])->name('api.cost-model.stored-data');
    
    // API untuk monitoring data
    Route::post('/upsert-monitoring', [CostModelController::class, 'upsertMonitoringData'])->name('api.cost-model.upsert-monitoring');
    Route::post('/upsert-existing-monitoring', [CostModelController::class, 'upsertExistingMonitoringData'])->name('api.cost-model.upsert-existing-monitoring');
    Route::get('/monitoring-data', [CostModelController::class, 'getMonitoringData'])->name('api.cost-model.monitoring-data');
    Route::get('/latest-monitoring-data', [CostModelController::class, 'getLatestMonitoringData'])->name('api.cost-model.latest-monitoring-data');
    Route::get('/all-unit-police-numbers', [CostModelController::class, 'getAllUnitPoliceNumbers'])->name('api.cost-model.all-unit-police-numbers');
    
    // API untuk perhitungan
    Route::post('/calculate', [CostModelController::class, 'calculate'])->name('api.cost-model.calculate');
    
    // API untuk dashboard data
    Route::get('/dashboard-data', [CostModelController::class, 'getDashboardData'])->name('api.cost-model.dashboard-data');
    
    // API untuk monitoring notes
    Route::post('/save-monitoring-note', [CostModelController::class, 'saveMonitoringNote'])->name('api.cost-model.save-monitoring-note');
    Route::get('/get-monitoring-note', [CostModelController::class, 'getMonitoringNote'])->name('api.cost-model.get-monitoring-note');
});
