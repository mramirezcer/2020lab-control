<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespuestaObservacion extends Model
{
    protected $table = 'respuestas_observacion';
    protected $primaryKey = 'id_respuesta_observacion';
    protected $guarded = [];

    public function observacion(): BelongsTo
    {
        return $this->belongsTo(ObservacionDirector::class, 'id_observacion', 'id_observacion');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}