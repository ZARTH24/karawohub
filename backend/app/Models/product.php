<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'nama',
        'motif',
        'bahan',
        'stok',
        'harga',
        'deskripsi',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
