<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';
    protected $fillable = ['farmacia_id', 'nome', 'descricao', 'preco'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'preco' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relação: Serviço pertence a uma Farmacia
     */
    public function farmacia(): BelongsTo
    {
        return $this->belongsTo(Farmacia::class, 'farmacia_id', 'id');
    }
}
