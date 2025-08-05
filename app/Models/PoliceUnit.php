<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'police_number',
        'unit_name',
        'unit_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan CostModelMonitoring melalui unit_police_number
     */
    public function monitoringRecords()
    {
        return $this->hasMany(CostModelMonitoring::class, 'unit_police_number', 'id');
    }

    /**
     * Scope untuk unit yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mencari berdasarkan nomor polisi
     */
    public function scopeByPoliceNumber($query, $policeNumber)
    {
        return $query->where('police_number', $policeNumber);
    }

    /**
     * Scope untuk join dengan monitoring data
     */
    public function scopeWithMonitoringData($query)
    {
        return $query->leftJoin('cost_model_monitoring', 'police_units.id', '=', 'cost_model_monitoring.unit_police_number')
                    ->select('police_units.*', 'cost_model_monitoring.year', 'cost_model_monitoring.week', 'cost_model_monitoring.component', 'cost_model_monitoring.value');
    }
} 