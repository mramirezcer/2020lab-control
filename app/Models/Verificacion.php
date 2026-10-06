<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verificacion extends Model
{
    protected $table = 'verificaciones';
    protected $primaryKey = 'id_verificacion';
    protected $guarded = [];

    protected function casts(): array
    {
        return ['verificado_at' => 'datetime'];
    }

    public function usuarioVerifica(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_verifica');
    }
}