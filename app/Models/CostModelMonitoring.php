<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModelMonitoring extends Model
{
    use HasFactory;

    protected $table = 'cost_model_monitoring';

    protected $fillable = [
        'unit_police_number',
        'year',
        'week',
        'component',
        'value',
        'note',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];
}
