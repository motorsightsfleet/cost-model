<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostModelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'units_price',
        'qty_units',
        'net_book_value',
        'solar_price',
        'adblue_price',
        'retase_per_day',
        'avg_ritase_per_day',
        'fuel_consumption',
        'adblue_consumption',
        'day_operation',
        'user_id',
    ];

    protected $casts = [
        'units_price' => 'decimal:2',
        'solar_price' => 'decimal:2',
        'adblue_price' => 'decimal:2',
        'avg_ritase_per_day' => 'decimal:2',
        'fuel_consumption' => 'decimal:2',
        'adblue_consumption' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculations()
    {
        return $this->hasMany(CostModelCalculation::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
