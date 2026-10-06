<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $primaryKey = 'id_actividad';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_inicio_planificada' => 'date',
            'fecha_entrega_comprometida' => 'date',
            'fecha_inicio_real' => 'datetime',
            'fecha_fin_real' => 'datetime',
            'porcentaje_avance' => 'decimal:2',
            'peso' => 'integer',
        ];
    }

    public function proceso(): BelongsTo
    {
        return $this->belongsTo(Proceso::class, 'id_proceso', 'id_proceso');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_verificador');
    }
}