<?php

namespace App\Imports;


use App\Models\Pagamento;
use App\Models\SaldosContabeis;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class LancamentosImport implements ToModel
{
    public function model(array $row)
    {
        // Ajuste os índices conforme a estrutura do seu Excel
        return new Pagamento([
            'saldos_contabeis_id' => $row[0],
            'valor' => $row[1],
            'data_pagamento' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]),
            'cp' => $row[3],
            'fornecedor' => $row[4],
            'notafiscal' => $row[5],
            'data_vencimento' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]),
            'valor_original' => $row[7],
            'descricao' => $row[8],
        ]);
    }
}