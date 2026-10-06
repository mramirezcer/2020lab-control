<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fase extends Model
{
    protected $table = 'fases';
    protected $primaryKey = 'id_fase';
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

    public function procesos(): HasMany
    {
        return $this->hasMany(Proceso::class, 'id_fase', 'id_fase')->orderBy('orden');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_verificador');
    }

    public function certificaciones(): HasMany
    {
        return $this->hasMany(CertificacionFase::class, 'id_fase', 'id_fase');
    }
}