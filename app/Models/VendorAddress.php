<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorAddress extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];
}
