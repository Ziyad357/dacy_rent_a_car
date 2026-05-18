<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'reservation_id',
        'contract_number',
        'signed_at',
        'fuel_level_out',
        'fuel_level_in',
        'mileage_out',
        'mileage_in',
        'condition_out',
        'condition_in',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'returned_at' => 'datetime',
            'mileage_out' => 'integer',
            'mileage_in' => 'integer',
        ];
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class);
    }
}
