<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VendorCompany extends Pivot
{
    protected $table = 'vendor_companies';

    public $incrementing = true;

    protected $fillable = [
        'vendor_id',
        'company_id',
        'is_primary',
        'vendor_code_for_company',
        'account_reference',
        'payment_terms_id',
        'credit_limit',
        'credit_currency_id',
        'purchase_currency_id',
        'default_tax_code_id',
        'default_payable_account',
        'default_expense_account',
        'purchase_enabled',
        'payment_enabled',
        'preferred_vendor',
        'minimum_order_value',
        'free_shipping_threshold',
        'lead_time_days',
        'price_level',
        'discount_percent',
        'withholding_tax_code_id',
        'effective_from',
        'effective_to',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'purchase_enabled' => 'boolean',
        'payment_enabled' => 'boolean',
        'preferred_vendor' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
