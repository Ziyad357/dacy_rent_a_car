<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'license_number',
        'license_expiry',
        'date_of_birth',
        'address',
        'blacklisted',
        'blacklist_reason',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
            'date_of_birth' => 'date',
            'blacklisted' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
