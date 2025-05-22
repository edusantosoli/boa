<?php

namespace App\Imports;

use App\Models\Pagamento;
use App\Models\SaldoContabil;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PagamentosImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function onRow(Row $row)
{
    $row = $row->toArray();

    // Ignorar linha se algum campo essencial estiver vazio
    if (empty($row['cp']) || empty($row['partida']) || empty($row['programa'])) {
        return;
    }

    try {
        $cp = trim($row['cp']);
        $partida = trim($row['partida']);
        $programa = trim($row['programa']);
        $fornecedor = $row['pagado_a'] ?? null;
        $notaFiscal = $row['nota_fiscal'] ?? null;
        $descricao = $row['descricao'] ?? null;

        // ✅ Limpar e converter datas (tratando quebras)
        try {
            // Detecta e converte data de vencimento
    $dataVencimentoBruto = trim($row['data_vencimento']);
    if (is_numeric($dataVencimentoBruto)) {
        $dataVencimento = Date::excelToDateTimeObject($dataVencimentoBruto)->format('Y-m-d');
    } else {
        $dataVencimento = \Carbon\Carbon::parse($dataVencimentoBruto)->format('Y-m-d');
    }

    // Detecta e converte data de pagamento
    $dataPagamentoBruto = trim($row['data_pagamento']);
    if (is_numeric($dataPagamentoBruto)) {
        $dataPagamento = Date::excelToDateTimeObject($dataPagamentoBruto)->format('Y-m-d');
    } else {
        $dataPagamento = \Carbon\Carbon::parse($dataPagamentoBruto)->format('Y-m-d');
    }

} catch (\Exception $e) {
    Log::warning("Data inválida para CP $cp: " . $e->getMessage());
    return;
        }

        // ✅ Limpar valores monetários
        $valorOriginal = $this->limparValor($row['valor_original']);
        $valorPago = $this->limparValor($row['valor_pago']);

        // Buscar saldo contábil com base em partida (conta) e programa (centro de custo)
        $saldo = SaldoContabil::whereHas('contaContabil', function ($q) use ($partida) {
                $q->where('codigo', $partida);
            })
            ->whereHas('centroDeCusto', function ($q) use ($programa) {
                $q->where('codigo', $programa);
            })
            ->with('contaContabil') // Garantir acesso ao tipo_servico_id
            ->first();

        if (!$saldo) {
            Log::warning("Saldo contábil não encontrado para partida: $partida e programa: $programa");
            return;
        }

        // Garantir que tipo_servico_id está definido
        $tipoServicoId = $saldo->contaContabil->tipo_servico_id ?? ($row['tipo_servico_id'] ?? null);
        if (!$tipoServicoId) {
            Log::warning("Tipo de serviço não definido para conta contábil nem informado na planilha (partida $partida, programa $programa)");
            return;
        }

        // Evitar duplicatas
        $existe = Pagamento::where('cp', $cp)
            ->where('notafiscal', $notaFiscal)
            ->where('valor', $valorPago)
            ->first();

        if ($existe) {
            return;
        }

        // Criar o pagamento
        Pagamento::create([
            'saldos_contabeis_id' => $saldo->id,
            'cp' => $cp,
            'fornecedor' => $fornecedor,
            'notafiscal' => $notaFiscal,
            'data_vencimento' => $dataVencimento,
            'data_pagamento' => $dataPagamento,
            'valor_original' => $valorOriginal,
            'valor' => $valorPago,
            'descricao' => $descricao,
            'tipo_servico_id' => $tipoServicoId,
        ]);

        // Atualizar saldo
        $saldo->saldo -= $valorPago;
        $saldo->save();

    } catch (\Exception $e) {
        Log::error('Erro ao importar pagamento: ' . $e->getMessage(), ['linha' => $row]);
    }
}


private function limparValor($valor)
{
    $valor = str_replace(['R$', ' ', '.'], '', $valor);
    $valor = str_replace(',', '.', $valor);
    return floatval($valor);
}

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}