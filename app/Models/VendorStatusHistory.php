<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorStatusHistory extends Model
{
    protected $fillable = ['vendor_id', 'from_status', 'to_status', 'reason', 'changed_by'];
}
