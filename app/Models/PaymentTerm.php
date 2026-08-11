<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $fillable = ['term_code', 'term_name', 'days', 'discount_days', 'discount_percent', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];
}
