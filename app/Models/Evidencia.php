<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    protected $primaryKey = 'id_evidencia';
    protected $guarded = [];

    protected function casts(): array
    {
        return ['registrado_at' => 'datetime'];
    }

    public function usuarioRegistra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario_registra');
    }
}