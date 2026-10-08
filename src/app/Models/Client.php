<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'client_code',
        'name',
        'phone',
        'email',
        'address',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Un cliente puede tener varios equipos.
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    // Un cliente puede tener varias órdenes de servicio.
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}