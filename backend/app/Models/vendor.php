<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'nama_toko',
        'pemilik',
        'legalitas',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validations()
    {
        return $this->hasMany(VendorValidation::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
