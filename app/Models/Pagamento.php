<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $casts = [
        'data_pagamento' => 'date',
        'data_vencimento' => 'date',
    ];

    protected $fillable = [
        'saldos_contabeis_id',
        'cp',
        'fornecedor',
        'notafiscal',
        'data_vencimento',
        'valor',
        'valor_original',
        'data_pagamento',
        'descricao',
        'tipo_servico_id',
    ];

    /**
     * Relacionamento com saldo contábil
     */
    public function saldoContabil()
    {
        return $this->belongsTo(SaldoContabil::class, 'saldos_contabeis_id');
    }

    /**
     * Relacionamento com tipo de serviço
     */
    public function tipoServico()
    {
        return $this->belongsTo(TipoServico::class, 'tipo_servico_id');
    }
}