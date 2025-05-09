<?php

namespace App\Exports;

use App\Models\CentroCustoExportModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CentrosDeCustoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CentroCustoExportModel::select('id', 'codigo', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Código', 'Criado em'];
    }
}
