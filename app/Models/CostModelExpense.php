<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModelExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_unit',
        'first_payment',
        'leasing_payment',
        'vehicle_tax',
        'kir',
        'telematics_one_time_cost',
        'telematics_recurring_cost',
        'tire_price',
        'lifetime_tyre',
        'oil_price',
        'pm_year_1',
        'pm_year_2',
        'pm_year_3',
        'pm_year_4',
        'pm_year_5',
        'pm_year_6',
        'pm_year_7',
        'pm_year_8',
        'pm_year_9',
        'pm_year_10',
        'gm_year_1',
        'gm_year_2',
        'gm_year_3',
        'gm_year_4',
        'gm_year_5',
        'gm_year_6',
        'gm_year_7',
        'gm_year_8',
        'gm_year_9',
        'gm_year_10',
        'toll_cost',
        'driver_per_unit',
        'driver_cost',
        'tyre_per_unit',
        'downtime_percentage',
    ];

    protected $casts = [
        'insurance_unit' => 'decimal:2',
        'first_payment' => 'decimal:2',
        'leasing_payment' => 'decimal:2',
        'vehicle_tax' => 'decimal:2',
        'kir' => 'decimal:2',
        'telematics_one_time_cost' => 'decimal:2',
        'telematics_recurring_cost' => 'decimal:2',
        'tire_price' => 'decimal:2',
        'lifetime_tyre' => 'decimal:2',
        'oil_price' => 'decimal:2',
        'pm_year_1' => 'decimal:2',
        'pm_year_2' => 'decimal:2',
        'pm_year_3' => 'decimal:2',
        'pm_year_4' => 'decimal:2',
        'pm_year_5' => 'decimal:2',
        'pm_year_6' => 'decimal:2',
        'pm_year_7' => 'decimal:2',
        'pm_year_8' => 'decimal:2',
        'pm_year_9' => 'decimal:2',
        'pm_year_10' => 'decimal:2',
        'gm_year_1' => 'decimal:2',
        'gm_year_2' => 'decimal:2',
        'gm_year_3' => 'decimal:2',
        'gm_year_4' => 'decimal:2',
        'gm_year_5' => 'decimal:2',
        'gm_year_6' => 'decimal:2',
        'gm_year_7' => 'decimal:2',
        'gm_year_8' => 'decimal:2',
        'gm_year_9' => 'decimal:2',
        'gm_year_10' => 'decimal:2',
        'toll_cost' => 'decimal:2',
        'driver_cost' => 'decimal:2',
        'downtime_percentage' => 'decimal:2',
    ];

    public function calculations()
    {
        return $this->hasMany(CostModelCalculation::class);
    }
}
