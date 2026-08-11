<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $fillable = ['category_code', 'category_name', 'active'];

    protected $casts = ['active' => 'boolean'];
}
