<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class VendorBankAccount extends Model
{
    protected $fillable = [
        'vendor_id',
        'company_id',
        'account_name',
        'bank_name',
        'bank_branch',
        'account_number_encrypted',
        'account_number_last4',
        'account_type',
        'routing_number',
        'ifsc_code',
        'swift_bic',
        'iban',
        'micr_code',
        'branch_code',
        'country',
        'currency_id',
        'beneficiary_name',
        'beneficiary_address',
        'payment_method_id',
        'is_primary',
        'is_verified',
        'verification_status',
        'verification_date',
        'verified_by',
        'verification_notes',
        'active',
        'effective_from',
        'effective_to',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'active' => 'boolean',
        'verification_date' => 'date',
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

    public function setAccountNumberAttribute(string $value): void
    {
        $digits = preg_replace('/\D+/', '', $value);
        $this->attributes['account_number_encrypted'] = Crypt::encryptString($value);
        $this->attributes['account_number_last4'] = substr($digits, -4);
    }

    public function getMaskedAccountNumberAttribute(): string
    {
        return 'XXXXXXXX' . $this->account_number_last4;
    }

    public function revealAccountNumber(): string
    {
        return Crypt::decryptString($this->account_number_encrypted);
    }
}
