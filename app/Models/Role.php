<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = ['nome', 'descricao'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public const ADMIN = 'admin';
    public const GERENTE = 'gerente';
    public const USUARIO = 'usuario';

    /**
     * Relação: Role tem muitos Usuários
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
