<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaldoContabil;
use App\Models\ContaContabil;
use App\Models\CentroDeCusto;


class SaldoContabilController extends Controller
{
    /**
     * Exibe a lista de saldos cadastrados.
     */
    public function index()
    {
        $saldos = SaldoContabil::with(['contaContabil', 'centroDeCusto'])
        ->orderBy('ano', 'desc')
        ->paginate(15);

    $saldosTotais = SaldoContabil::selectRaw('conta_contabil_id, centro_de_custo_id, ano, SUM(valor) as total')
        ->groupBy('conta_contabil_id', 'centro_de_custo_id', 'ano')
        ->get()
        ->keyBy(fn($item) => $item->conta_contabil_id . '-' . $item->centro_de_custo_id . '-' . $item->ano);

         return view('saldos.index', compact('saldos', 'saldosTotais'));
    }

    /**
     * Exibe o formulário de criação de novo saldo.
     */
    public function create()
    {
        $contas = ContaContabil::all();
        $centros = CentroDeCusto::all();
        return view('saldos.create', compact('contas', 'centros'));
    }

    /**
     * Armazena um novo saldo no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conta_contabil_id' => 'required|exists:conta_contabils,id',
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
            'ano' => 'required|digits:4',
            'valor' => 'required|numeric',
        ]);
    
        // Adiciona saldo com o mesmo valor informado
        $dados = $request->all();
        $dados['saldo'] = $dados['valor'];
    
        SaldoContabil::create($dados);
    
        return redirect()->route('saldos.index')->with('success', 'Saldo cadastrado com sucesso.');
    }

    /**
     * Exibe os detalhes de um saldo específico.
     */
    public function show(SaldoContabil $saldo)
    {
        return view('saldos.show', compact('saldo'));
    }

    /**
     * Exibe o formulário de edição de um saldo.
     */
    public function edit(SaldoContabil $saldo)
    {
        $contas = ContaContabil::all();
        $centros = CentroDeCusto::all();
        return view('saldos.edit', compact('saldo', 'contas', 'centros'));
    }

    /**
     * Atualiza um saldo no banco de dados.
     */
    public function update(Request $request, SaldoContabil $saldo)
    {
        $request->validate([
            'conta_contabil_id' => 'required|exists:conta_contabils,id',
            'centro_de_custo_id' => 'required|exists:centros_de_custo,id',
            'ano' => 'required|digits:4',
            'valor' => 'required|numeric',
        ]);

        $saldo->update($request->all());

        return redirect()->route('saldos.index')->with('success', 'Saldo atualizado com sucesso.');
    }

    /**
     * Remove um saldo do banco de dados.
     */
    public function destroy(SaldoContabil $saldo)
    {
        $saldo->delete();
        return redirect()->route('saldos.index')->with('success', 'Saldo removido com sucesso.');
    }
}