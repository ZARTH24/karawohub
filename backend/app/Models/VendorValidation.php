<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorValidation extends Model
{
    protected $fillable = [
        'vendor_id',
        'admin_id',
        'status',
        'alasan'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
