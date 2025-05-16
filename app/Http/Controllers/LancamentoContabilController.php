<?php

namespace App\Http\Controllers;

use App\Models\LancamentoContabil;
use App\Models\CentroDeCusto;
use App\Models\ContaContabil;
use App\Models\SaldoContabil;
use Illuminate\Http\Request;

class LancamentoContabilController extends Controller
{
    public function index()
    {
        $lancamentos = LancamentoContabil::with(['centroDeCusto', 'contaContabil'])->orderByDesc('id')->paginate(15);
        return view('lancamentos.index', compact('lancamentos'));
    }

    public function create()
    {
        $centros = CentroDeCusto::whereIn('id', function ($query) {
            $query->select('centro_de_custo_id')->from('saldos_contabeis')->distinct();
        })->get();

        $contasContabeis = ContaContabil::all();

        return view('lancamentos.create', compact('centros', 'contasContabeis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
            'conta_contabil_id' => 'required|exists:contas_contabeis,id',
            'programa' => 'required|string',
            'pagando_a' => 'required|string',
            'nota_fiscal' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'data_baixa' => 'nullable|date',
            'valor_original' => 'required|numeric',
            'valor_pago' => 'required|numeric',
            'glosa' => 'nullable|numeric',
        ]);

        $lancamento = LancamentoContabil::create($request->all());

        $ano = date('Y', strtotime($request->data_baixa ?? $request->data_vencimento));

        $saldo = SaldoContabil::firstOrCreate([
            'conta_contabil_id' => $request->conta_contabil_id,
            'centro_de_custo_id' => $request->centro_de_custo_id,
            'ano' => $ano
        ]);

        $saldo->valor -= $request->valor_pago;
        $saldo->save();

        return redirect()->route('lancamentos.index')->with('success', 'Lançamento criado com sucesso e saldo atualizado.');
    }

    public function show(LancamentoContabil $lancamento)
    {
        return view('lancamentos.show', compact('lancamento'));
    }

    public function edit(LancamentoContabil $lancamento)
    {
        $centros = CentroDeCusto::whereIn('id', function ($query) {
            $query->select('centro_de_custo_id')->from('saldos_contabeis')->distinct();
        })->get();
    
        $contasContabeis = ContaContabil::all();
    
        return view('lancamentos.edit', compact('lancamento', 'centros', 'contasContabeis'));
    }

    public function update(Request $request, LancamentoContabil $lancamento)
    {
        $request->validate([
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
            'conta_contabil_id' => 'required|exists:contas_contabeis,id',
            'programa' => 'required|string',
            'pagando_a' => 'required|string',
            'nota_fiscal' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'data_baixa' => 'nullable|date',
            'valor_original' => 'required|numeric',
            'valor_pago' => 'required|numeric',
            'glosa' => 'nullable|numeric',
        ]);

        // Reverte o saldo antigo
        $anoAntigo = date('Y', strtotime($lancamento->data_baixa ?? $lancamento->data_vencimento));
        $saldoAntigo = SaldoContabil::where([
            'conta_contabil_id' => $lancamento->conta_contabil_id,
            'centro_de_custo_id' => $lancamento->centro_de_custo_id,
            'ano' => $anoAntigo
        ])->first();

        if ($saldoAntigo) {
            $saldoAntigo->valor += $lancamento->valor_pago;
            $saldoAntigo->save();
        }

        $lancamento->update($request->all());

        // Aplica novo saldo
        $anoNovo = date('Y', strtotime($request->data_baixa ?? $request->data_vencimento));
        $saldoNovo = SaldoContabil::firstOrCreate([
            'conta_contabil_id' => $request->conta_contabil_id,
            'centro_de_custo_id' => $request->centro_de_custo_id,
            'ano' => $anoNovo
        ]);

        $saldoNovo->valor -= $request->valor_pago;
        $saldoNovo->save();

        return redirect()->route('lancamentos.index')->with('success', 'Lançamento atualizado com sucesso.');
    }

    public function destroy(LancamentoContabil $lancamento)
    {
        $ano = date('Y', strtotime($lancamento->data_baixa ?? $lancamento->data_vencimento));

        $saldo = SaldoContabil::where([
            'conta_contabil_id' => $lancamento->conta_contabil_id,
            'centro_de_custo_id' => $lancamento->centro_de_custo_id,
            'ano' => $ano
        ])->first();

        if ($saldo) {
            $saldo->valor += $lancamento->valor_pago;
            $saldo->save();
        }

        $lancamento->delete();

        return redirect()->route('lancamentos.index')->with('success', 'Lançamento excluído e saldo ajustado.');
    }
}