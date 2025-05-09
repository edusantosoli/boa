<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoContabil extends Model
{
    protected $table = 'saldos_contabeis';
    protected $fillable = ['conta_contabil_id', 'centro_de_custo_id', 'ano', 'valor'];

    public function contaContabil()
    {
        return $this->belongsTo(ContaContabil::class);
    }

    public function centroDeCusto()
    {
        return $this->belongsTo(CentroDeCusto::class);
    }
}
