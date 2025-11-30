<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{

    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'paket',
        'aktif_mulai',
        'aktif_sampai',
        'status'
    ];

    protected $casts = [
        'aktif_mulai' => 'datetime',
        'aktif_sampai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
