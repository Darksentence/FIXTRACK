<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosis extends Model
{
    protected $table = 'diagnoses';

    protected $fillable = [
        'service_order_id',
        'diagnosis',
        'proposed_solution',
        'observations',
        'diagnosed_at',
    ];

    protected $casts = [
        'diagnosed_at' => 'datetime',
    ];

    // Cada diagnóstico pertenece a una orden de servicio.
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}