<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    protected $table = 'order_histories';

    protected $fillable = [
        'service_order_id',
        'user_id',
        'previous_status',
        'new_status',
        'notes',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // Cada registro del historial pertenece a una orden.
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    // Usuario que realizó el cambio.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}