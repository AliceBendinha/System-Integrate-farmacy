<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    protected $fillable = ['nome', 'codigo', 'preco', 'data_validade', 'categoria_id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'preco' => 'decimal:2',
        'data_validade' => 'date:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['em_falta'];

    /**
     * Relação: Produto pertence a uma Categoria
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    /**
     * Relação: Produto tem muitos Estoques
     */
    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class, 'produto_id', 'id');
    }

    /**
     * Relação: Produto está em muitas Farmacias através de Estoques
     */
    public function farmacias(): BelongsToMany
    {
        return $this->belongsToMany(
            Farmacia::class,
            'estoques',
            'produto_id',
            'farmacia_id'
        )->withPivot('quantidade', 'stock_minimo')->withTimestamps();
    }

    /**
     * Accessor: Verifica se produto está em falta em qualquer farmacia
     */
    public function getEmFaltaAttribute(): bool
    {
        return $this->estoques()->where('quantidade', '<', DB::raw('stock_minimo'))->exists();
    }

    /**
     * Verifica se produto está vencido
     */
    public function estaVencido(): bool
    {
        return $this->data_validade && $this->data_validade->isPast();
    }
}
