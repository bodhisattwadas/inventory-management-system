<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function scopeExpiringWithin($query, int $days)
    {
        return $query->whereDate('expiry_date', '>=', now())
            ->whereDate('expiry_date', '<=', now()->addDays($days));
    }
}
