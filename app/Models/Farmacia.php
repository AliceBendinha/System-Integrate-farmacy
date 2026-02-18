<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farmacia extends Model
{
    use HasFactory;

    protected $table = 'farmacias';
    protected $fillable = ['nome', 'localizacao', 'user_id'];
    protected $hidden = ['user_id', 'created_at', 'updated_at'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relação: Farmacia pertence a um Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relação: Farmacia tem muitos Estoques
     */
    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class, 'farmacia_id', 'id');
    }

    /**
     * Relação: Farmacia tem muitos Produtos através de Estoques
     */
    public function produtos()
    {
        return $this->belongsToMany(
            Produto::class,
            'estoques',
            'farmacia_id',
            'produto_id'
        )->withPivot('quantidade', 'stock_minimo')->withTimestamps();
    }

    /**
     * Relação: Farmacia tem muitos Serviços
     */
    public function servicos(): HasMany
    {
        return $this->hasMany(Servico::class, 'farmacia_id', 'id');
    }

    /**
     * Relação: Farmacia tem Localizações
     */
    public function localizacoes(): HasMany
    {
        return $this->hasMany(Localizacao::class, 'farmacia_id', 'id');
    }
}
