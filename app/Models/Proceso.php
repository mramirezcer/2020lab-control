<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proceso extends Model
{
    protected $table = 'procesos';
    protected $primaryKey = 'id_proceso';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_inicio_planificada' => 'date',
            'fecha_entrega_comprometida' => 'date',
            'fecha_inicio_real' => 'datetime',
            'fecha_fin_real' => 'datetime',
            'porcentaje_avance' => 'decimal:2',
        ];
    }

    public function fase(): BelongsTo
    {
        return $this->belongsTo(Fase::class, 'id_fase', 'id_fase');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'id_proceso', 'id_proceso')->orderBy('orden');
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