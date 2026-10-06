<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionDirector extends Model
{
    protected $table = 'revisiones_director';
    protected $primaryKey = 'id_revision';
    protected $guarded = [];

    protected function casts(): array
    {
        return ['revisado_at' => 'datetime'];
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_director');
    }
}