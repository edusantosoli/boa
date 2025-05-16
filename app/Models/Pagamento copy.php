<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'saldos_contabeis_id',
        'cp',
        'fornecedor',
        'notafiscal',
        'data_vencimento',
        'valor',
        'data_pagamento',
        'descricao',
    ];

    /**
     * Relacionamento com o saldo contábil (centro de custo)
     */
    public function saldoContabil()
    {
        return $this->belongsTo(SaldosContabeis::class, 'saldos_contabeis_id');
    }
}