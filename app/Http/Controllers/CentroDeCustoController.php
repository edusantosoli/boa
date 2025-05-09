<?php

namespace App\Http\Controllers;
use App\Exports\CentrosDeCustoExport;
use App\Models\CentroDeCusto;
use App\Models\CentroCustoExportModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF; // DomPDF

class CentroDeCustoController extends Controller
{
    public function index()
    {
        $centros = CentroDeCusto::all();
        return view('centros.index', compact('centros'));
    }

    public function create()
    {
        return view('centros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|max:255',
        ]);

        CentroDeCusto::create($request->all());
        return redirect()->route('centros.index')->with('success', 'Centro de Custo criado!');
    }

    public function edit(CentroDeCusto $centro)
    {
        return view('centros.edit', compact('centro'));
    }

    public function update(Request $request, CentroDeCusto $centro)
    {
        $request->validate([
            'codigo' => 'required|max:255',
        ]);

        $centro->update($request->all());
        return redirect()->route('centros.index')->with('success', 'Atualizado com sucesso!');
    }

    public function destroy(CentroDeCusto $centro)
    {
        $centro->delete();
        return redirect()->route('centros.index')->with('success', 'Deletado com sucesso!');
    }
    public function exportExcel()
    {
        return Excel::download(new CentrosDeCustoExport, 'centros_de_custo.xlsx');
    }
    
    public function exportPdf()
    {
        $centros = CentroCustoExportModel::all();
        $pdf = PDF::loadView('centros.pdf', compact('centros'));
        return $pdf->download('centros_de_custo.pdf');
    }

}