<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\SaldosContabeis;
use Illuminate\Http\Request;
use App\Models\SaldoContabil;
use App\Imports\LancamentosImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TipoServico;
class PagamentoController extends Controller
{
    // Listar todos os pagamentos
    public function index()
    {
        $pagamentos = Pagamento::with('saldoContabil.centroDeCusto','saldoContabil.contaContabil')->latest()->paginate(10);
        return view('pagamentos.index', compact('pagamentos'));
    }

    // Mostrar formulário de criação
    public function create()
    {
        $saldos = SaldoContabil::with(['centroDeCusto', 'contaContabil'])->get();

    // Aqui estamos buscando apenas tipos de serviço com conta contábil associada
    $tiposServicos = TipoServico::with('contaContabil')
        ->whereNotNull('conta_contabil_id')
        ->get();

    return view('pagamentos.create', compact('saldos', 'tiposServicos'));
    }

    // Armazenar novo pagamento
    public function store(Request $request)
    {
        $request->validate([
            'saldos_contabeis_id' => 'required|exists:saldos_contabeis,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'cp' => 'nullable|string|max:10',
            'fornecedor' => 'nullable|string|max:255',
            'notafiscal' => 'nullable|string|max:50',
            'data_vencimento' => 'required|date', // O input vem como Y-m-d mesmo que você exiba como d/m/Y
            'valor_original' => 'nullable|numeric|min:0.01',
            'descricao' => 'nullable|string|max:255',
            'tipo_servico_id' => 'required|exists:tipo_servicos,id',
        ]);

        $saldo = SaldosContabeis::findOrFail($request->saldos_contabeis_id);

        if ($request->valor > $saldo->saldo) {
            return back()->withErrors(['valor' => 'O valor excede o saldo disponível.'])->withInput();
        }

        // Criar pagamento
        $pagamento = Pagamento::create($request->all());

        // Abater valor do saldo
        $saldo->saldo -= $request->valor;
        $saldo->save();

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento registrado com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $pagamento = Pagamento::findOrFail($id);
       
        $saldos = SaldoContabil::with('centroDeCusto','contaContabil')->get();
        $contaContabilId = $pagamento->saldoContabil->conta_contabil_id ?? null;

        $tipos_servico = [];
        if ($contaContabilId){
            $tipos_servico = TipoServico::where('conta_contabil_id', $contaContabilId)->get();
        }

        return view('pagamentos.edit', compact('pagamento', 'saldos'))->with('tipos_servico', $tipos_servico);
    }

    // Atualizar pagamento
    public function update(Request $request, $id)
    {
        $request->validate([
            'saldos_contabeis_id' => 'required|exists:saldos_contabeis,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'cp' => 'nullable|string|max:10',
            'fornecedor' => 'nullable|string|max:255',
            'notafiscal' => 'nullable|string|max:50',
            'data_vencimento' => 'nullable|date_format:Y-m-d', // O input vem como Y-m-d mesmo que você exiba como d/m/Y
            'valor_Original' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string|max:255',
            'tipo_servico_id' => 'required|exists:tipo_servicos,id',
        ]);

        $pagamento = Pagamento::findOrFail($id);

        // Repor saldo anterior
        $saldoAntigo = SaldosContabeis::findOrFail($pagamento->saldos_contabeis_id);
        $saldoAntigo->saldo += $pagamento->valor;
        $saldoAntigo->save();

        // Aplicar novo pagamento
        $novoSaldo = SaldosContabeis::findOrFail($request->saldos_contabeis_id);

        if ($request->valor > $novoSaldo->saldo) {
            return back()->withErrors(['valor' => 'O valor excede o saldo disponível.'])->withInput();
        }

        // Atualizar pagamento
        $pagamento->update($request->all());

        // Abater novo valor
        $novoSaldo->saldo -= $request->valor;
        $novoSaldo->save();

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento atualizado com sucesso!');
    }

    // Excluir pagamento
    public function destroy($id)
    {
        $pagamento = Pagamento::findOrFail($id);
        $saldo = SaldosContabeis::findOrFail($pagamento->saldos_contabeis_id);

        // Repor o valor ao saldo
        $saldo->saldo += $pagamento->valor;
        $saldo->save();

        $pagamento->delete();

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento excluído com sucesso!');
    }

    public function importar(Request $request)
{
    $request->validate([
        'arquivo' => 'required|file|mimes:xlsx,xls',
    ]);

    Excel::import(new LancamentosImport, $request->file('arquivo'));

    return redirect()->route('pagamentos.index')->with('success', 'Lançamentos importados com sucesso!');
}
}
