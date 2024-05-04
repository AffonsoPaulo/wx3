<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model {
    use HasFactory;

    protected $fillable = [
        'street',
        'city',
        'neighborhood',
        'state',
        'number',
        'zipCode',
        'client_id',
    ];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function sale(): HasMany {
        return $this->hasMany(Sale::class);
    }
}
