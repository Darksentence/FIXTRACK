<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrder extends Model
{
    protected $table = 'service_orders';

    protected $fillable = [
        'order_code',
        'client_id',
        'equipment_id',
        'service_type',
        'reported_problem',
        'status',
        'authorization_status',
        'authorized_at',
        'estimated_cost',
        'final_cost',
        'received_at',
        'completed_at',
        'delivered_at',
    ];

    protected $casts = [
        'authorized_at' => 'datetime',
        'received_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
    ];

    // Cada orden pertenece a un cliente.
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Cada orden pertenece a un equipo.
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    // Cada orden puede tener un diagnóstico principal.
    public function diagnosis(): HasOne
    {
        return $this->hasOne(Diagnosis::class);
    }

    // Cada orden puede tener una reparación principal.
    public function repair(): HasOne
    {
        return $this->hasOne(Repair::class);
    }

    // Cada orden puede registrar múltiples cambios.
    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
}