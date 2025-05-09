<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\Request;
use App\Exports\ContasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class ContaController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ContasExport, 'contas.xlsx');

    }

    public function exportPDF()
    {
        $contas = Conta::all();
        $pdf = Pdf::loadView('contas.pdf', compact('contas'));
        return $pdf->download('contas.pdf');
    }
    
}

