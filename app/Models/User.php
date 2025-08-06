<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi dengan CostModelSetting
     */
    public function costModelSettings()
    {
        return $this->hasMany(CostModelSetting::class);
    }

    /**
     * Relasi dengan CostModelExpense
     */
    public function costModelExpenses()
    {
        return $this->hasMany(CostModelExpense::class);
    }

    /**
     * Relasi dengan CostModelCalculation
     */
    public function costModelCalculations()
    {
        return $this->hasMany(CostModelCalculation::class);
    }

    /**
     * Relasi dengan CostModelMonitoring
     */
    public function costModelMonitorings()
    {
        return $this->hasMany(CostModelMonitoring::class);
    }

    /**
     * Relasi dengan PoliceUnit
     */
    public function policeUnits()
    {
        return $this->hasMany(PoliceUnit::class);
    }
}
