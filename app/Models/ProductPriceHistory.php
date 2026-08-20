<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceHistory extends Model
{
    protected $fillable = [
        'product_id',
        'changed_by',
        'source',
        'reference',
        'old_mrp',
        'new_mrp',
        'notes',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'changed_by' => 'integer',
        'old_mrp' => 'decimal:2',
        'new_mrp' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
