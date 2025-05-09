<?php

namespace App\Exports;

use App\Models\TipoServico;
use Maatwebsite\Excel\Concerns\FromCollection;


class TipoServicosExport implements FromCollection
{
    public function collection()
    {
        return TipoServico::all();
    }
}
