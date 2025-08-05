<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModelCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_id',
        'expense_id',
        'unit_down_payment',
        'financing',
        'leasing_payment_yearly',
        'avg_ret_per_month',
        'avg_ret_per_year',
        'fuel_consumption_per_ret',
        'fuel_consumption_per_month',
        'fuel_consumption_per_year',
        'solar_per_year',
        'adblue_consumption_per_day',
        'adblue_consumption_per_month',
        'adblue_consumption_per_year',
        'driver_cost_per_month',
        'driver_cost_per_year',
        'cost_per_unit',
        'idr_per_km',
        'idr_per_km_unit',
        'cost_days',
        'cost_month',
        'cost_year',
        'telematics_cost_per_month',
        'telematics_cost_first_year',
        'telematics_cost_subsequent_years',
        'total_cost_non_units',
        'downtime_cost_estimate',
        'yearly_breakdown',
        'dashboard_data',
    ];

    protected $casts = [
        'unit_down_payment' => 'decimal:2',
        'financing' => 'decimal:2',
        'leasing_payment_yearly' => 'decimal:2',
        'avg_ret_per_month' => 'decimal:2',
        'avg_ret_per_year' => 'decimal:2',
        'fuel_consumption_per_ret' => 'decimal:2',
        'fuel_consumption_per_month' => 'decimal:2',
        'fuel_consumption_per_year' => 'decimal:2',
        'solar_per_year' => 'decimal:2',
        'adblue_consumption_per_day' => 'decimal:2',
        'adblue_consumption_per_month' => 'decimal:2',
        'adblue_consumption_per_year' => 'decimal:2',
        'driver_cost_per_month' => 'decimal:2',
        'driver_cost_per_year' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'idr_per_km' => 'decimal:2',
        'idr_per_km_unit' => 'decimal:2',
        'cost_days' => 'decimal:2',
        'cost_month' => 'decimal:2',
        'cost_year' => 'decimal:2',
        'telematics_cost_per_month' => 'decimal:2',
        'telematics_cost_first_year' => 'decimal:2',
        'telematics_cost_subsequent_years' => 'decimal:2',
        'total_cost_non_units' => 'decimal:2',
        'downtime_cost_estimate' => 'decimal:2',
        'yearly_breakdown' => 'array',
        'dashboard_data' => 'array',
    ];

    public function setting()
    {
        return $this->belongsTo(CostModelSetting::class);
    }

    public function expense()
    {
        return $this->belongsTo(CostModelExpense::class);
    }
}
