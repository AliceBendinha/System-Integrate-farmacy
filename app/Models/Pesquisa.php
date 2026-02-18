<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesquisa extends Model
{
    use HasFactory;

    protected $table = 'pesquisas';
    protected $fillable = ['usuario_id', 'termo', 'resultados_encontrados', 'tipo_pesquisa'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'resultados_encontrados' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relação: Pesquisa pertence a um Usuário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
}
