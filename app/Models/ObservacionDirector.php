<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObservacionDirector extends Model
{
    protected $table = 'observaciones_director';
    protected $primaryKey = 'id_observacion';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'abierta_at' => 'datetime',
            'cerrada_at' => 'datetime',
        ];
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_director');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(RespuestaObservacion::class, 'id_observacion', 'id_observacion');
    }
}