<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoques';
    protected $fillable = ['farmacia_id', 'produto_id', 'quantidade', 'stock_minimo'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'quantidade' => 'integer',
        'stock_minimo' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['em_falta', 'percentual_estoque'];

    /**
     * Relação: Estoque pertence a uma Farmacia
     */
    public function farmacia(): BelongsTo
    {
        return $this->belongsTo(Farmacia::class, 'farmacia_id', 'id');
    }

    /**
     * Relação: Estoque pertence a um Produto
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    /**
     * Accessor: Verifica se está em falta
     */
    public function getEmFaltaAttribute(): bool
    {
        return $this->quantidade < $this->stock_minimo;
    }

    /**
     * Accessor: Percentual de estoque em relação ao mínimo
     */
    public function getPercentualEstoqueAttribute(): float
    {
        return $this->stock_minimo > 0 ? round(($this->quantidade / $this->stock_minimo) * 100, 2) : 0;
    }

    /**
     * Repor estoque
     */
    public function repor(int $quantidade): void
    {
        $this->quantidade += $quantidade;
        $this->save();
    }

    /**
     * Remover do estoque
     */
    public function remover(int $quantidade): bool
    {
        if ($this->quantidade >= $quantidade) {
            $this->quantidade -= $quantidade;
            $this->save();
            return true;
        }
        return false;
    }
}
