<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldosContabeis extends Model
{
    use HasFactory;

    protected $table = 'saldos_contabeis';

    protected $fillable = [
        'centro_de_custo',
        'saldo',
    ];
    public function centroDeCusto()
    {
        return $this->belongsTo(\App\Models\CentroDeCusto::class, 'centro_de_custo_id');
    }
    public function contaContabil()
    {
        return $this->belongsTo(\App\Models\ContaContabil::class, 'conta_contabil_id');
    }
}