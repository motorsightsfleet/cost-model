<?php

namespace App\Http\Controllers;

use App\Models\CostModelSetting;
use App\Models\CostModelExpense;
use App\Models\CostModelCalculation;
use App\Models\CostModelMonitoring;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CostModelController extends Controller
{
    /**
     * Menampilkan halaman utama cost model calculator
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Menyimpan atau mengupdate semua data settings dan expenses dalam satu request
     * Menggunakan upsert (insert or update) - hanya menyimpan satu record
     */
    public function upsertAllData(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Validasi data
            $request->validate([
                // Settings - Actual
                'units_price' => 'nullable|numeric|min:0',
                'qty_units' => 'nullable|integer|min:0',
                'net_book_value' => 'nullable|integer|min:0',
                'solar_price' => 'nullable|numeric|min:0',
                'adblue_price' => 'nullable|numeric|min:0',
                
                // Settings - Assumption
                'retase_per_day' => 'nullable|integer|min:0',
                'avg_ritase_per_day' => 'nullable|numeric|min:0',
                'fuel_consumption' => 'nullable|numeric|min:0',
                'adblue_consumption' => 'nullable|numeric|min:0',
                'day_operation' => 'nullable|integer|min:0',
                
                // Expenses - Actual
                'insurance_unit' => 'nullable|numeric|min:0',
                'first_payment' => 'nullable|numeric|min:0',
                'leasing_payment' => 'nullable|numeric|min:0',
                'vehicle_tax' => 'nullable|numeric|min:0',
                'kir' => 'nullable|numeric|min:0',
                'telematics_one_time_cost' => 'nullable|numeric|min:0',
                'telematics_recurring_cost' => 'nullable|numeric|min:0',
                'tire_price' => 'nullable|numeric|min:0',
                'lifetime_tyre' => 'nullable|numeric|min:0',
                'oil_price' => 'nullable|numeric|min:0',
                
                // PM Costs (10 tahun)
                'pm_year_1' => 'nullable|numeric|min:0',
                'pm_year_2' => 'nullable|numeric|min:0',
                'pm_year_3' => 'nullable|numeric|min:0',
                'pm_year_4' => 'nullable|numeric|min:0',
                'pm_year_5' => 'nullable|numeric|min:0',
                'pm_year_6' => 'nullable|numeric|min:0',
                'pm_year_7' => 'nullable|numeric|min:0',
                'pm_year_8' => 'nullable|numeric|min:0',
                'pm_year_9' => 'nullable|numeric|min:0',
                'pm_year_10' => 'nullable|numeric|min:0',
                
                // GM Costs (10 tahun)
                'gm_year_1' => 'nullable|numeric|min:0',
                'gm_year_2' => 'nullable|numeric|min:0',
                'gm_year_3' => 'nullable|numeric|min:0',
                'gm_year_4' => 'nullable|numeric|min:0',
                'gm_year_5' => 'nullable|numeric|min:0',
                'gm_year_6' => 'nullable|numeric|min:0',
                'gm_year_7' => 'nullable|numeric|min:0',
                'gm_year_8' => 'nullable|numeric|min:0',
                'gm_year_9' => 'nullable|numeric|min:0',
                'gm_year_10' => 'nullable|numeric|min:0',
                
                // Expenses - Assumption
                'toll_cost' => 'nullable|numeric|min:0',
                'driver_per_unit' => 'nullable|integer|min:0',
                'driver_cost' => 'nullable|numeric|min:0',
                'tyre_per_unit' => 'nullable|integer|min:0',
                'downtime_percentage' => 'nullable|numeric|min:0|max:100',
            ]);

            // Upsert Settings - hanya satu record
            $settingData = [
                'units_price' => $request->units_price ?? 0,
                'qty_units' => $request->qty_units ?? 0,
                'net_book_value' => $request->net_book_value ?? 0,
                'solar_price' => $request->solar_price ?? 0,
                'adblue_price' => $request->adblue_price ?? 0,
                'retase_per_day' => $request->retase_per_day ?? 0,
                'avg_ritase_per_day' => $request->avg_ritase_per_day ?? 0,
                'fuel_consumption' => $request->fuel_consumption ?? 0,
                'adblue_consumption' => $request->adblue_consumption ?? 0,
                'day_operation' => $request->day_operation ?? 0,
            ];

            // Hapus semua settings yang ada dan buat yang baru
            CostModelSetting::truncate();
            $setting = CostModelSetting::create($settingData);

            // Upsert Expenses - hanya satu record
            $expenseData = [
                'insurance_unit' => $request->insurance_unit ?? 0,
                'first_payment' => $request->first_payment ?? 0,
                'leasing_payment' => $request->leasing_payment ?? 0,
                'vehicle_tax' => $request->vehicle_tax ?? 0,
                'kir' => $request->kir ?? 0,
                'telematics_one_time_cost' => $request->telematics_one_time_cost ?? 0,
                'telematics_recurring_cost' => $request->telematics_recurring_cost ?? 0,
                'tire_price' => $request->tire_price ?? 0,
                'lifetime_tyre' => $request->lifetime_tyre ?? 0,
                'oil_price' => $request->oil_price ?? 0,
                'pm_year_1' => $request->pm_year_1 ?? 0,
                'pm_year_2' => $request->pm_year_2 ?? 0,
                'pm_year_3' => $request->pm_year_3 ?? 0,
                'pm_year_4' => $request->pm_year_4 ?? 0,
                'pm_year_5' => $request->pm_year_5 ?? 0,
                'pm_year_6' => $request->pm_year_6 ?? 0,
                'pm_year_7' => $request->pm_year_7 ?? 0,
                'pm_year_8' => $request->pm_year_8 ?? 0,
                'pm_year_9' => $request->pm_year_9 ?? 0,
                'pm_year_10' => $request->pm_year_10 ?? 0,
                'gm_year_1' => $request->gm_year_1 ?? 0,
                'gm_year_2' => $request->gm_year_2 ?? 0,
                'gm_year_3' => $request->gm_year_3 ?? 0,
                'gm_year_4' => $request->gm_year_4 ?? 0,
                'gm_year_5' => $request->gm_year_5 ?? 0,
                'gm_year_6' => $request->gm_year_6 ?? 0,
                'gm_year_7' => $request->gm_year_7 ?? 0,
                'gm_year_8' => $request->gm_year_8 ?? 0,
                'gm_year_9' => $request->gm_year_9 ?? 0,
                'gm_year_10' => $request->gm_year_10 ?? 0,
                'toll_cost' => $request->toll_cost ?? 0,
                'driver_per_unit' => $request->driver_per_unit ?? 0,
                'driver_cost' => $request->driver_cost ?? 0,
                'tyre_per_unit' => $request->tyre_per_unit ?? 0,
                'downtime_percentage' => $request->downtime_percentage ?? 0,
            ];

            // Hapus semua expenses yang ada dan buat yang baru
            CostModelExpense::truncate();
            $expense = CostModelExpense::create($expenseData);

            // Lakukan perhitungan dan simpan hasilnya
            $calculation = $this->performCalculation($setting, $expense);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan/ diperbarui',
                'data' => [
                    'setting' => $setting,
                    'expense' => $expense,
                    'calculation' => $calculation
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data settings dan expenses yang tersimpan
     */
    public function getStoredData(): JsonResponse
    {
        try {
            $setting = CostModelSetting::first();
            $expense = CostModelExpense::first();
            $calculation = CostModelCalculation::with(['setting', 'expense'])->latest()->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'setting' => $setting,
                    'expense' => $expense,
                    'calculation' => $calculation
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan data monitoring untuk komponen tertentu
     */
    public function upsertMonitoringData(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'unit_police_number' => 'nullable|string',
                'year' => 'required|integer|min:1|max:10',
                'week' => 'required|integer|min:0|max:52', // Allow week = 0 for metadata
                'component' => 'required|string',
                'value' => 'nullable|numeric|min:0',
                'note' => 'nullable|string',
            ]);

            // Log data yang akan disimpan
            Log::info('Upserting monitoring data:', [
                'unit_police_number' => $request->unit_police_number,
                'year' => $request->year,
                'week' => $request->week,
                'component' => $request->component,
                'value' => $request->value,
                'note' => $request->note,
            ]);

            // Cek apakah data sudah ada
            $existingRecord = CostModelMonitoring::where([
                'unit_police_number' => $request->unit_police_number,
                'year' => $request->year,
                'week' => $request->week,
                'component' => $request->component,
            ])->first();

            if ($existingRecord) {
                Log::info('Found existing record, updating:', ['id' => $existingRecord->id, 'old_value' => $existingRecord->value]);
            } else {
                Log::info('No existing record found, creating new one');
            }

            // Upsert monitoring data
            $monitoring = CostModelMonitoring::updateOrCreate(
                [
                    'unit_police_number' => $request->unit_police_number,
                    'year' => $request->year,
                    'week' => $request->week,
                    'component' => $request->component,
                ],
                [
                    'value' => $request->value ?? 0,
                    'note' => $request->note,
                ]
            );

            Log::info('Upsert completed:', ['id' => $monitoring->id, 'wasRecentlyCreated' => $monitoring->wasRecentlyCreated]);

            return response()->json([
                'success' => true,
                'message' => 'Data monitoring berhasil disimpan/diperbarui',
                'data' => $monitoring
            ]);

        } catch (\Exception $e) {
            Log::error('Error in upsertMonitoringData:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan data monitoring untuk existing monitoring
     */
    public function upsertExistingMonitoringData(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'unit_police_number' => 'nullable|string',
                'year' => 'required|integer|min:1|max:10',
                'week' => 'required|integer|min:0|max:52', // Allow week = 0 for metadata
                'component' => 'required|string',
                'value' => 'nullable|numeric|min:0',
                'note' => 'nullable|string',
            ]);

            // Log data yang akan disimpan
            Log::info('Upserting existing monitoring data:', [
                'unit_police_number' => $request->unit_police_number,
                'year' => $request->year,
                'week' => $request->week,
                'component' => 'existing_' . $request->component,
                'value' => $request->value,
                'note' => $request->note,
            ]);

            // Cek apakah data sudah ada
            $existingRecord = CostModelMonitoring::where([
                'unit_police_number' => $request->unit_police_number,
                'year' => $request->year,
                'week' => $request->week,
                'component' => 'existing_' . $request->component,
            ])->first();

            if ($existingRecord) {
                Log::info('Found existing record, updating:', ['id' => $existingRecord->id, 'old_value' => $existingRecord->value]);
            } else {
                Log::info('No existing record found, creating new one');
            }

            // Upsert existing monitoring data dengan prefix 'existing_'
            $monitoring = CostModelMonitoring::updateOrCreate(
                [
                    'unit_police_number' => $request->unit_police_number,
                    'year' => $request->year,
                    'week' => $request->week,
                    'component' => 'existing_' . $request->component,
                ],
                [
                    'value' => $request->value ?? 0,
                    'note' => $request->note,
                ]
            );

            Log::info('Upsert existing completed:', ['id' => $monitoring->id, 'wasRecentlyCreated' => $monitoring->wasRecentlyCreated]);

            return response()->json([
                'success' => true,
                'message' => 'Data existing monitoring berhasil disimpan/diperbarui',
                'data' => $monitoring
            ]);

        } catch (\Exception $e) {
            Log::error('Error in upsertExistingMonitoringData:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data monitoring berdasarkan filter
     */
    public function getMonitoringData(Request $request): JsonResponse
    {
        try {
            $query = CostModelMonitoring::query();

            if ($request->has('unit_police_number') && $request->unit_police_number) {
                $query->where('unit_police_number', $request->unit_police_number);
            }

            if ($request->has('year') && $request->year) {
                $query->where('year', $request->year);
            }

            if ($request->has('week') && $request->week) {
                $query->where('week', $request->week);
            }

            if ($request->has('component') && $request->component) {
                $query->where('component', $request->component);
            }

            $monitoring = $query->get();

            return response()->json([
                'success' => true,
                'data' => $monitoring
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil nopol terakhir yang diinputkan dan semua data monitoring-nya
     */
    public function getLatestMonitoringData(Request $request): JsonResponse
    {
        try {
            // Ambil nopol terakhir yang diinputkan (berdasarkan updated_at terbaru)
            $latestUnit = CostModelMonitoring::select('unit_police_number')
                ->whereNotNull('unit_police_number')
                ->where('unit_police_number', '!=', '')
                ->orderBy('updated_at', 'desc')
                ->first();

            if (!$latestUnit) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'latest_unit' => null,
                    'message' => 'Tidak ada data monitoring yang ditemukan'
                ]);
            }

            $latestUnitNumber = $latestUnit->unit_police_number;

            // Ambil semua data monitoring untuk nopol terakhir
            $monitoringData = CostModelMonitoring::where('unit_police_number', $latestUnitNumber)
                ->orderBy('year', 'asc')
                ->orderBy('week', 'asc')
                ->orderBy('component', 'asc')
                ->get();

            // Kelompokkan data berdasarkan tahun dan minggu
            $groupedData = [];
            foreach ($monitoringData as $data) {
                $year = $data->year;
                $week = $data->week;
                
                if (!isset($groupedData[$year])) {
                    $groupedData[$year] = [];
                }
                
                if (!isset($groupedData[$year][$week])) {
                    $groupedData[$year][$week] = [];
                }
                
                $groupedData[$year][$week][] = [
                    'id' => $data->id,
                    'component' => $data->component,
                    'value' => $data->value,
                    'note' => $data->note,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $groupedData,
                'latest_unit' => $latestUnitNumber,
                'message' => 'Data monitoring berhasil diambil'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getLatestMonitoringData:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar semua nopol yang pernah diinputkan
     */
    public function getAllUnitPoliceNumbers(Request $request): JsonResponse
    {
        try {
            $units = CostModelMonitoring::select('unit_police_number')
                ->whereNotNull('unit_police_number')
                ->where('unit_police_number', '!=', '')
                ->distinct()
                ->orderBy('unit_police_number', 'asc')
                ->pluck('unit_police_number');

            return response()->json([
                'success' => true,
                'data' => $units,
                'message' => 'Daftar nopol berhasil diambil'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAllUnitPoliceNumbers:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghitung cost model dan menyimpan hasilnya
     */
    public function calculate(Request $request): JsonResponse
    {
        try {
            $setting = CostModelSetting::first();
            $expense = CostModelExpense::first();

            if (!$setting || !$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data settings dan expenses belum tersedia'
                ], 400);
            }

            // Melakukan perhitungan berdasarkan logika dari HTML
            $calculation = $this->performCalculation($setting, $expense);

            return response()->json([
                'success' => true,
                'message' => 'Perhitungan berhasil',
                'data' => $calculation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Melakukan perhitungan cost model
     */
    private function performCalculation($setting, $expense)
    {
        // Helper function untuk safe division
        $safeDivision = function($numerator, $denominator) {
            return ($denominator && $denominator > 0) ? $numerator / $denominator : 0;
        };

        // Helper function untuk safe multiplication dengan null check
        $safeMultiply = function($a, $b) {
            return ($a ?? 0) * ($b ?? 0);
        };

        // Unit Payment
        $unitDownPayment = $safeMultiply($setting->units_price, 0.3);
        $financing = $safeMultiply($setting->units_price, 0.7);
        $leasingPaymentYearly = $safeMultiply($expense->leasing_payment, 12);

        // Retase
        $avgRetPerMonth = $safeMultiply($setting->avg_ritase_per_day, $setting->day_operation);
        $avgRetPerYear = $safeMultiply($avgRetPerMonth, 12);

        // Fuel Consumption
        $fuelConsumptionPerRet = $safeDivision($setting->avg_ritase_per_day, $setting->fuel_consumption);
        $fuelConsumptionPerMonth = $safeMultiply($fuelConsumptionPerRet, $setting->day_operation);
        $fuelConsumptionPerYear = $safeMultiply($fuelConsumptionPerMonth, 12);
        $solarPerYear = $safeMultiply($setting->solar_price, $fuelConsumptionPerYear);

        // AdBlue
        $adblueConsumptionPerDay = $safeMultiply(
            $safeDivision($setting->avg_ritase_per_day, $setting->adblue_consumption),
            $setting->adblue_price
        );
        $adblueConsumptionPerMonth = $safeMultiply($adblueConsumptionPerDay, $setting->day_operation);
        $adblueConsumptionPerYear = $safeMultiply($adblueConsumptionPerMonth, 12);

        // Operation
        $driverCostPerMonth = $safeMultiply($expense->driver_cost, $setting->day_operation) * ($setting->retase_per_day ?? 0) + $safeMultiply($expense->toll_cost, $setting->day_operation);
        $driverCostPerYear = $safeMultiply($driverCostPerMonth, 12);

        // Tyre Management
        $costPerUnit = $safeMultiply($expense->tire_price, $expense->tyre_per_unit);
        $idrPerKm = $safeDivision($expense->tire_price, $expense->lifetime_tyre);
        $idrPerKmUnit = $safeMultiply($idrPerKm, $expense->tyre_per_unit);
        $costDays = $safeMultiply($idrPerKmUnit, $setting->avg_ritase_per_day);
        $costMonth = $safeMultiply($idrPerKmUnit, $avgRetPerMonth);
        $costYear = $safeMultiply($idrPerKmUnit, $avgRetPerYear);

        // Telematics Module
        $telematicsCostPerMonth = $expense->telematics_recurring_cost ?? 0;
        $telematicsCostFirstYear = $safeMultiply($expense->telematics_recurring_cost, 12) + ($expense->telematics_one_time_cost ?? 0);
        $telematicsCostSubsequentYears = $safeMultiply($expense->telematics_recurring_cost, 12);

        // PM Costs dengan adjustment oil price
        $pmCosts = [];
        for ($i = 1; $i <= 10; $i++) {
            $pmField = "pm_year_{$i}";
            $pmCost = $expense->$pmField ?? 0;
            $adjustedPMCost = $pmCost;
            
            if (($expense->oil_price ?? 0) > 0) {
                $adjustedPMCost = $i === 1 
                    ? ($pmCost - 2800000) + ($safeMultiply($expense->oil_price, 20) * 2)
                    : ($pmCost - 1400000) + $safeMultiply($expense->oil_price, 20);
            }
            $pmCosts[] = $adjustedPMCost;
        }

        // GM Costs
        $gmCosts = [];
        for ($i = 1; $i <= 10; $i++) {
            $gmField = "gm_year_{$i}";
            $gmCosts[] = $expense->$gmField ?? 0;
        }

        // Total Cost non Units (untuk 10 tahun)
        $totalCostNonUnits = 
            $safeMultiply($expense->vehicle_tax, 10) + 
            ($expense->insurance_unit ?? 0) + 
            $safeMultiply($expense->kir, 10) + 
            $safeMultiply($solarPerYear, 10) + 
            $safeMultiply($adblueConsumptionPerYear, 10) + 
            $safeMultiply($driverCostPerYear, 10) + 
            array_sum($pmCosts) + 
            array_sum($gmCosts) + 
            $safeMultiply($costYear, 10) +
            $telematicsCostFirstYear + $safeMultiply($telematicsCostSubsequentYears, 9);

        // Downtime Cost Estimate
        $downtimeCostEstimate = $safeMultiply($totalCostNonUnits, $safeDivision($expense->downtime_percentage, 100));

        // Yearly breakdown
        $yearlyBreakdown = [];
        for ($year = 1; $year <= 10; $year++) {
            $yearlyBreakdown[$year] = [
                'vehicle_tax' => $expense->vehicle_tax ?? 0,
                'insurance' => $year === 1 ? ($expense->insurance_unit ?? 0) : 0,
                'kir' => $expense->kir ?? 0,
                'solar' => $solarPerYear,
                'adblue' => $adblueConsumptionPerYear,
                'driver_cost' => $driverCostPerYear,
                'pm_cost' => $pmCosts[$year - 1] ?? 0,
                'gm_cost' => $gmCosts[$year - 1] ?? 0,
                'tyre_cost' => $costYear,
                'telematics_cost' => $year === 1 ? $telematicsCostFirstYear : $telematicsCostSubsequentYears,
                'leasing_payment' => $year <= 3 ? $leasingPaymentYearly : 0,
                'first_payment' => $year === 1 ? ($expense->first_payment ?? 0) : 0,
                'unit_down_payment' => $year === 1 ? $unitDownPayment : 0,
            ];
        }

        // Dashboard data untuk disimpan ke database
        $dashboardRows = [
            ['category' => 'Actual', 'label' => 'Harga Units', 'assumption' => $setting->units_price ?? 0, 'values' => array_fill(0, 10, 0)],
            ['category' => 'Actual', 'label' => 'Uang Muka (30%)', 'assumption' => $unitDownPayment, 'values' => array_merge([$unitDownPayment], array_fill(0, 9, 0))],
            ['category' => 'Actual', 'label' => 'Pembiayaan (70%)', 'assumption' => $financing, 'values' => array_merge(array_fill(0, 3, $leasingPaymentYearly), array_fill(0, 7, 0))],
            ['category' => 'Actual', 'label' => 'First Payment', 'assumption' => $expense->first_payment ?? 0, 'values' => array_merge([$expense->first_payment ?? 0], array_fill(0, 9, 0))],
            ['category' => 'Actual', 'label' => 'Pajak & STNK', 'assumption' => $expense->vehicle_tax ?? 0, 'values' => array_merge([0], array_fill(0, 9, $expense->vehicle_tax ?? 0))],
            ['category' => 'Actual', 'label' => 'Asuransi', 'assumption' => $expense->insurance_unit ?? 0, 'values' => array_merge([$expense->insurance_unit ?? 0], array_fill(0, 9, 0))],
            ['category' => 'Actual', 'label' => 'KIR', 'assumption' => $expense->kir ?? 0, 'values' => array_fill(0, 10, $expense->kir ?? 0)],
            ['category' => 'Actual', 'label' => 'Telematics Module', 'assumption' => 'Yearly', 'values' => array_merge([$telematicsCostFirstYear], array_fill(0, 9, $telematicsCostSubsequentYears))],
            ['category' => 'Assumption', 'label' => 'Service Berkala/PM', 'assumption' => 'Yearly', 'values' => array_slice($pmCosts, 0, 10)],
            ['category' => 'Assumption', 'label' => 'Service General/GM', 'assumption' => 'Yearly', 'values' => array_slice($gmCosts, 0, 10)],
            ['category' => 'Assumption', 'label' => 'BBM', 'assumption' => $solarPerYear, 'values' => array_fill(0, 10, $solarPerYear)],
            ['category' => 'Assumption', 'label' => 'AdBlue', 'assumption' => $adblueConsumptionPerYear, 'values' => array_fill(0, 10, $adblueConsumptionPerYear)],
            ['category' => 'Assumption', 'label' => 'Driver Cost', 'assumption' => $driverCostPerYear, 'values' => array_fill(0, 10, $driverCostPerYear)],
            ['category' => 'Assumption', 'label' => 'Ban', 'assumption' => $costYear, 'values' => array_fill(0, 10, $costYear)],
            ['category' => 'Assumption', 'label' => 'Downtime (1%)', 'assumption' => ($expense->downtime_percentage ?? 0) . '%', 'values' => array_fill(0, 10, $downtimeCostEstimate)]
        ];

        // Hitung yearly totals
        $yearlyTotals = array_fill(0, 10, 0);
        $actualTotals = array_fill(0, 10, 0);
        $assumptionTotals = array_fill(0, 10, 0);

        foreach ($dashboardRows as $row) {
            for ($i = 0; $i < 10; $i++) {
                $value = $row['values'][$i] ?? 0;
                if ($row['category'] === 'Actual') {
                    $actualTotals[$i] += $value;
                } else {
                    $assumptionTotals[$i] += $value;
                }
                $yearlyTotals[$i] += $value;
            }
        }

        // Hapus calculation lama dan buat yang baru
        CostModelCalculation::truncate();
        
        // Simpan hasil perhitungan
        $calculation = CostModelCalculation::create([
            'setting_id' => $setting->id,
            'expense_id' => $expense->id,
            'unit_down_payment' => $unitDownPayment,
            'financing' => $financing,
            'leasing_payment_yearly' => $leasingPaymentYearly,
            'avg_ret_per_month' => $avgRetPerMonth,
            'avg_ret_per_year' => $avgRetPerYear,
            'fuel_consumption_per_ret' => $fuelConsumptionPerRet,
            'fuel_consumption_per_month' => $fuelConsumptionPerMonth,
            'fuel_consumption_per_year' => $fuelConsumptionPerYear,
            'solar_per_year' => $solarPerYear,
            'adblue_consumption_per_day' => $adblueConsumptionPerDay,
            'adblue_consumption_per_month' => $adblueConsumptionPerMonth,
            'adblue_consumption_per_year' => $adblueConsumptionPerYear,
            'driver_cost_per_month' => $driverCostPerMonth,
            'driver_cost_per_year' => $driverCostPerYear,
            'cost_per_unit' => $costPerUnit,
            'idr_per_km' => $idrPerKm,
            'idr_per_km_unit' => $idrPerKmUnit,
            'cost_days' => $costDays,
            'cost_month' => $costMonth,
            'cost_year' => $costYear,
            'telematics_cost_per_month' => $telematicsCostPerMonth,
            'telematics_cost_first_year' => $telematicsCostFirstYear,
            'telematics_cost_subsequent_years' => $telematicsCostSubsequentYears,
            'total_cost_non_units' => $totalCostNonUnits,
            'downtime_cost_estimate' => $downtimeCostEstimate,
            'yearly_breakdown' => json_encode($yearlyBreakdown),
            'dashboard_data' => json_encode([
                'rows' => $dashboardRows,
                'yearly_totals' => $yearlyTotals,
                'actual_totals' => $actualTotals,
                'assumption_totals' => $assumptionTotals,
                'grand_total' => array_sum($yearlyTotals)
            ])
        ]);

        return $calculation;
    }

    /**
     * Mengambil data dashboard dari database
     */
    public function getDashboardData(): JsonResponse
    {
        try {
            $calculation = CostModelCalculation::first();

            if (!$calculation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perhitungan belum tersedia'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data dashboard berhasil diambil',
                'data' => [
                    'dashboard_data' => $calculation->dashboard_data,
                    'calculation' => $calculation
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
