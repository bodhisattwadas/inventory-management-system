<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'preferred' => 'boolean',
        'active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];
}
