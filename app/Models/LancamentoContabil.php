<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoContabil extends Model
{
    use HasFactory;
    protected $table = 'lancamentos_contabeis';
    
    protected $fillable = [
        'programa',
        'centro_de_custo_id',
        'pagando_a',
        'nota_fiscal',
        'data_vencimento',
        'data_baixa',
        'valor_original',
        'valor_pago',
        'glosa',
        'conta_contabil_id',
    ];

    public function centroDeCusto()
    {
        return $this->belongsTo(CentroDeCusto::class, 'centro_de_custo_id');
    }
}
