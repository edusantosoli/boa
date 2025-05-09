<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContaContabil;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContasExport;

class ContaContabilController extends Controller
{
    public function index()
    {
        $contas = ContaContabil::all();
        return view('contas.index', compact('contas'));
    }

    public function create()
    {
        return view('contas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:conta_contabils,codigo',
            'descricao' => 'required',
        ]);

        ContaContabil::create($request->all());

        return redirect()->route('contas.index')->with('success', 'Conta cadastrada com sucesso.');
    }

    public function edit(ContaContabil $conta)
    {
        return view('contas.edit', compact('conta'));
    }

    public function update(Request $request, ContaContabil $conta)
    {
        $request->validate([
            'codigo' => 'required|unique:conta_contabils,codigo,' . $conta->id,
            'descricao' => 'required',
        ]);

        $conta->update($request->all());

        return redirect()->route('contas.index')->with('success', 'Conta atualizada com sucesso.');
    }

    public function destroy(ContaContabil $conta)
    {
        $conta->delete();
        return redirect()->route('contas.index')->with('success', 'Conta excluída com sucesso.');
    }

    public function exportExcel()
    {
    return Excel::download(new ContasExport, 'contas.xlsx');
    }
}
