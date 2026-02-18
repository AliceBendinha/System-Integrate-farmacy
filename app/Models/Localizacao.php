<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Localizacao extends Model
{
    use HasFactory;

    protected $table = 'localizacoes';
    protected $fillable = ['farmacia_id', 'endereco', 'latitude', 'longitude', 'cep'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relação: Localização pertence a uma Farmacia
     */
    public function farmacia(): BelongsTo
    {
        return $this->belongsTo(Farmacia::class, 'farmacia_id', 'id');
    }

    /**
     * Calcular distância para um ponto (Haversine)
     */
    public function distanciaAte(float $lat, float $long): float
    {
        $lat1 = deg2rad($this->latitude);
        $lat2 = deg2rad($lat);
        $deltaLat = deg2rad($lat - $this->latitude);
        $deltaLong = deg2rad($long - $this->longitude);

        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLong / 2) ** 2;
        $c = 2 * asin(sqrt($a));
        $raioTerra = 6371; // km

        return $raioTerra * $c;
    }
}
