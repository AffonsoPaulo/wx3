<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model {
    use HasFactory;

    protected $fillable = [
        'total',
        'shipping',
        'discount',
        'paymentMethod',
        'client_id',
        'address_id',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function address(): BelongsTo {
        return $this->belongsTo(Address::class);
    }

    public function saleproducts(): HasMany {
        return $this->hasMany(SaleProduct::class);
    }
}
