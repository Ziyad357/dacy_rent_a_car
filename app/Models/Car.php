<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'plate_number',
        'color',
        'fuel_type',
        'transmission',
        'seats',
        'mileage',
        'daily_rate',
        'deposit_amount',
        'status',
        'image',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'seats' => 'integer',
            'mileage' => 'integer',
            'daily_rate' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(CarMaintenance::class);
    }
}
