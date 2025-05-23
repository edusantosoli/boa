<?php


namespace App\Http\Controllers;

use App\Models\TipoServico;
use App\Models\ContaContabil;
use Illuminate\Http\Request;

class TipoServicoController extends Controller
{
    public function index()
    {
        $tipos = TipoServico::with('contaContabil')->get();
        return view('tipo_servicos.index', compact('tipos'));
    }

    public function create()
    {
        $contas = ContaContabil::all();
        return view('tipo_servicos.create', compact('contas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'conta_contabil_id' => 'required|exists:conta_contabils,id',
        ]);

        TipoServico::create($request->all());

        return redirect()->route('tipo-servicos.index')->with('success', 'Tipo de serviço criado com sucesso.');
    }

    public function edit($id)
    {
        $tipo = TipoServico::findOrFail($id);
        $contas = ContaContabil::all();
        return view('tipo_servicos.edit', compact('tipo', 'contas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'conta_contabil_id' => 'required|exists:conta_contabils,id',
        ]);

        $tipo = TipoServico::findOrFail($id);
        $tipo->update($request->all());

        return redirect()->route('tipo-servicos.index')->with('success', 'Atualizado com sucesso.');
    }

    public function destroy($id)
    {
        TipoServico::findOrFail($id)->delete();

        return redirect()->route('tipo-servicos.index')->with('success', 'Excluído com sucesso.');
    }

    public function porConta($contaId)
{
    $tipos = TipoServico::where('conta_contabil_id', $contaId)->get();

    return response()->json($tipos);
}
}
