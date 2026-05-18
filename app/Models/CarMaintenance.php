<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarMaintenance extends Model
{
    protected $fillable = [
        'car_id',
        'type',
        'description',
        'cost',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'started_at' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
