<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModelMonitoring extends Model
{
    use HasFactory;

    protected $table = 'cost_model_monitoring';

    protected $fillable = [
        'unit_police_number', // Sekarang sebagai integer (foreign key ke police_units.id)
        'year',
        'week',
        'component',
        'value',
        'note',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'unit_police_number' => 'integer',
    ];

    /**
     * Relasi dengan PoliceUnit melalui unit_police_number
     */
    public function policeUnit()
    {
        return $this->belongsTo(PoliceUnit::class, 'unit_police_number', 'id');
    }

    /**
     * Accessor untuk mendapatkan nomor polisi dari relasi
     */
    public function getPoliceNumberAttribute()
    {
        return $this->policeUnit ? $this->policeUnit->police_number : null;
    }

    /**
     * Scope untuk mencari berdasarkan police unit id
     */
    public function scopeByPoliceUnitId($query, $policeUnitId)
    {
        return $query->where('unit_police_number', $policeUnitId);
    }

    /**
     * Scope untuk mencari berdasarkan nomor polisi (menggunakan relasi)
     */
    public function scopeByPoliceNumber($query, $policeNumber)
    {
        return $query->whereHas('policeUnit', function ($q) use ($policeNumber) {
            $q->where('police_number', $policeNumber);
        });
    }

    /**
     * Scope untuk join dengan police_units
     */
    public function scopeWithPoliceUnit($query)
    {
        return $query->join('police_units', 'cost_model_monitoring.unit_police_number', '=', 'police_units.id')
                    ->select('cost_model_monitoring.*', 'police_units.police_number', 'police_units.unit_name', 'police_units.unit_type');
    }
}
