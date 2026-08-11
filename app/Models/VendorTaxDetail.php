<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorTaxDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'withholding_tax_applicable' => 'boolean',
        'tax_exempt' => 'boolean',
        'msme_registered' => 'boolean',
        'verified' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'tax_exemption_expiry' => 'date',
    ];
}
