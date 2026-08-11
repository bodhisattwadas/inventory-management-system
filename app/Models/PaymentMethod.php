<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['method_code', 'method_name', 'requires_bank_account', 'active'];

    protected $casts = ['requires_bank_account' => 'boolean', 'active' => 'boolean'];
}
