<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $table = 'equipments';

    protected $fillable = [
        'client_id',
        'equipment_type',
        'brand',
        'model',
        'serial_number',
        'description',
    ];

    // Cada equipo pertenece a un cliente.
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Un equipo puede tener varias órdenes de servicio.
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}