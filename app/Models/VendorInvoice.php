<?php

namespace App\Models;

use App\Enums\VendorInvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorInvoice extends Model
{
    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'company_id',
        'po_reference',
        'invoice_number',
        'invoice_date',
        'amount',
        'paid_amount',
        'document_path',
        'status',
        'payment_method',
        'payment_reference',
        'payment_notes',
        'paid_at',
        'paid_by',
    ];

    protected $casts = [
        'purchase_id' => 'integer',
        'supplier_id' => 'integer',
        'company_id' => 'integer',
        'invoice_date' => 'date',
        'amount' => 'integer',
        'paid_amount' => 'integer',
        'status' => VendorInvoiceStatus::class,
        'paid_at' => 'datetime',
        'paid_by' => 'integer',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
