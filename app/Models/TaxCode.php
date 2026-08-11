<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxCode extends Model
{
    protected $fillable = ['code', 'name', 'rate', 'active'];

    protected $casts = ['active' => 'boolean'];
}
