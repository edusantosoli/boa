<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\SaldoContabil;
use App\Models\CentroDeCusto;
use App\Models\TipoServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PagamentosImport;

class PagamentoController extends Controller
{
    // Listagem com filtros
    public function index(Request $request)
    {
        $query = Pagamento::with([
            'saldoContabil.centroDeCusto',
            'saldoContabil.contaContabil',
            'tipoServico'
        ]);

        if ($request->filled('centro_custo_id')) {
            $query->whereHas('saldoContabil.centroDeCusto', function ($q) use ($request) {
                $q->where('id', $request->centro_custo_id);
            });
        }

        if ($request->filled('tipo_servico_id')) {
            $query->where('tipo_servico_id', $request->tipo_servico_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_pagamento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_pagamento', '<=', $request->data_fim);
        }

        $pagamentos = $query->orderByDesc('data_pagamento')->paginate(10);

        $centros_custo = CentroDeCusto::orderBy('descricao')->get();
        $tipos_servico = TipoServico::orderBy('nome')->get();

        return view('pagamentos.index', compact('pagamentos', 'centros_custo', 'tipos_servico'));
    }

    // Exibir formulário de criação
    public function create()
    {
        $saldos = SaldoContabil::with(['centroDeCusto', 'contaContabil'])->get();

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
            'data_vencimento' => 'required|date',
            'valor_original' => 'nullable|numeric|min:0.01',
            'descricao' => 'nullable|string|max:255',
            'tipo_servico_id' => 'required|exists:tipo_servicos,id',
        ]);

        DB::transaction(function () use ($request) {
            $saldo = SaldoContabil::findOrFail($request->saldos_contabeis_id);

            if ($request->valor > $saldo->saldo) {
                throw new \Exception('O valor excede o saldo disponível.');
            }

            Pagamento::create($request->all());

            $saldo->saldo -= $request->valor;
            $saldo->save();
        });

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento registrado com sucesso!');
    }

    // Exibir formulário de edição
    public function edit($id)
    {
        $pagamento = Pagamento::findOrFail($id);
        $saldos = SaldoContabil::with('centroDeCusto', 'contaContabil')->get();

        $tipos_servico = [];
        $contaContabilId = $pagamento->saldoContabil->conta_contabil_id ?? null;

        if ($contaContabilId) {
            $tipos_servico = TipoServico::where('conta_contabil_id', $contaContabilId)->get();
        }

        return view('pagamentos.edit', compact('pagamento', 'saldos', 'tipos_servico'));
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
            'data_vencimento' => 'nullable|date',
            'valor_original' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string|max:255',
            'tipo_servico_id' => 'required|exists:tipo_servicos,id',
        ]);

        DB::transaction(function () use ($request, $id) {
            $pagamento = Pagamento::findOrFail($id);

            // Repor saldo anterior
            $saldoAntigo = SaldoContabil::findOrFail($pagamento->saldos_contabeis_id);
            $saldoAntigo->saldo += $pagamento->valor;
            $saldoAntigo->save();

            // Aplicar novo saldo
            $novoSaldo = SaldoContabil::findOrFail($request->saldos_contabeis_id);

            if ($request->valor > $novoSaldo->saldo) {
                throw new \Exception('O valor excede o saldo disponível.');
            }

            $pagamento->update($request->all());

            $novoSaldo->saldo -= $request->valor;
            $novoSaldo->save();
        });

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento atualizado com sucesso!');
    }

    // Excluir pagamento
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pagamento = Pagamento::findOrFail($id);
            $saldo = SaldoContabil::findOrFail($pagamento->saldos_contabeis_id);

            $saldo->saldo += $pagamento->valor;
            $saldo->save();

            $pagamento->delete();
        });

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento excluído com sucesso!');
    }

    public function destroySelecionados(Request $request)
    {
     $ids = $request->input('selecionados', []);

    if (empty($ids)) {
        return redirect()->route('pagamentos.index')->with('error', 'Nenhum pagamento selecionado.');
    }

    \App\Models\Pagamento::whereIn('id', $ids)->delete();

    return redirect()->route('pagamentos.index')->with('success', 'Pagamentos selecionados excluídos com sucesso.');
    }




    // View de importação
    public function importarPagamentosView()
    {
        return view('pagamentos.importar');
    }

    // Importar planilha de pagamentos
    public function importarPagamentos(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PagamentosImport, $request->file('arquivo'));
            return back()->with('success', 'Pagamentos importados com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['arquivo' => 'Erro ao importar: ' . $e->getMessage()]);
        }
    }

    // Redirecionar show para index
    public function show($id)
    {
        return redirect()->route('pagamentos.index');
    }

    public function porConta($contaId)
    {
        $tipos = TipoServico::where('conta_contabil_id', $contaId)->get();
        return response()->json($tipos);
    }
}
