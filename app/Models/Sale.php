<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'shipping',
        'discount',
        'paymentMethod',
        'client_id',
        'address_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
