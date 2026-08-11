<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vendor_code',
        'vendor_name',
        'legal_name',
        'trade_name',
        'vendor_type',
        'vendor_category_id',
        'vendor_group_id',
        'parent_vendor_id',
        'business_type',
        'registration_number',
        'incorporation_date',
        'website',
        'industry',
        'business_description',
        'primary_contact_person',
        'primary_email',
        'accounts_email',
        'po_email',
        'primary_phone',
        'alternate_phone',
        'default_payment_terms_id',
        'default_currency_id',
        'default_payment_method_id',
        'preferred_vendor',
        'purchase_enabled',
        'payment_enabled',
        'allow_advance_payment',
        'status',
        'approval_status',
        'risk_level',
        'blocked',
        'block_type',
        'blocked_reason',
        'blocked_by',
        'blocked_at',
        'unblock_reason',
        'unblocked_by',
        'unblocked_at',
        'blacklisted',
        'blacklist_reason',
        'created_by',
        'updated_by',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
    ];

    protected $casts = [
        'incorporation_date' => 'date',
        'preferred_vendor' => 'boolean',
        'purchase_enabled' => 'boolean',
        'payment_enabled' => 'boolean',
        'allow_advance_payment' => 'boolean',
        'blocked' => 'boolean',
        'blacklisted' => 'boolean',
        'blocked_at' => 'datetime',
        'unblocked_at' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'vendor_companies')
            ->using(VendorCompany::class)
            ->withPivot([
                'is_primary',
                'vendor_code_for_company',
                'payment_terms_id',
                'credit_limit',
                'purchase_currency_id',
                'purchase_enabled',
                'payment_enabled',
                'preferred_vendor',
                'lead_time_days',
                'status',
                'notes',
            ])
            ->withTimestamps();
    }

    public function vendorCompanies(): HasMany
    {
        return $this->hasMany(VendorCompany::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(VendorContact::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(VendorAddress::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(VendorBankAccount::class);
    }

    public function taxDetails(): HasMany
    {
        return $this->hasMany(VendorTaxDetail::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VendorItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(VendorStatusHistory::class);
    }

    public function scopePurchasable($query)
    {
        return $query->where('status', 'active')
            ->where('approval_status', 'approved')
            ->where('blocked', false)
            ->where('purchase_enabled', true);
    }

    public function dropdownLabel(): string
    {
        return "{$this->vendor_code} : {$this->vendor_name}";
    }
}
