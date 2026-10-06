<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domain\Seguimiento\RolesProyecto;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'ultimo_acceso_at' => 'datetime',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function tieneRol(string ...$roles): bool
    {
        if (! $this->activo || ! $this->rol) {
            return false;
        }

        return in_array($this->rol->clave, $roles, true);
    }

    public function esDirector(): bool
    {
        return $this->tieneRol(RolesProyecto::DIRECTOR);
    }

    public function esLiderProyecto(): bool
    {
        return $this->tieneRol(RolesProyecto::LIDER_PROYECTO);
    }

    public function esAdministrador(): bool
    {
        return $this->tieneRol(RolesProyecto::ADMINISTRADOR);
    }
}