<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificacionFase extends Model
{
    protected $table = 'certificaciones_fase';
    protected $primaryKey = 'id_certificacion';
    protected $guarded = [];

    protected function casts(): array
    {
        return ['certificado_at' => 'datetime'];
    }

    public function fase(): BelongsTo
    {
        return $this->belongsTo(Fase::class, 'id_fase', 'id_fase');
    }

    public function usuarioCertifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_certifica');
    }
}