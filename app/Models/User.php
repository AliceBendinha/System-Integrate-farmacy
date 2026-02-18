<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relação: Usuário pertence a uma Role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Relação: Usuário tem muitas Farmacias
     */
    public function farmacias(): HasMany
    {
        return $this->hasMany(Farmacia::class, 'user_id', 'id');
    }

    /**
     * Relação: Usuário tem muitas Pesquisas
     */
    public function pesquisas(): HasMany
    {
        return $this->hasMany(Pesquisa::class, 'usuario_id', 'id');
    }

    /**
     * Verifica se usuário é admin
     */
    public function isAdmin(): bool
    {
        return $this->role?->nome === Role::ADMIN;
    }

    /**
     * Verifica se usuário é gerente
     */
    public function isGerente(): bool
    {
        return $this->role?->nome === Role::GERENTE;
    }

    /**
     * Verifica se usuário pode acessar farmacia
     */
    public function temAcessoAFarmacia(int $farmaciaId): bool
    {
        return $this->isAdmin() || $this->farmacias()->where('id', $farmaciaId)->exists();
    }
}
