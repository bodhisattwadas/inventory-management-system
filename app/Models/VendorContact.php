<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorContact extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_primary' => 'boolean',
        'receives_po' => 'boolean',
        'receives_payment_advice' => 'boolean',
        'receives_rfq' => 'boolean',
        'receives_statement' => 'boolean',
        'active' => 'boolean',
    ];
}
