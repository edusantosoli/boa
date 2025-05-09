<?php

namespace App\Exports;

use App\Models\Conta;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContasExport implements FromCollection
{
    public function collection()
    {
        return Conta::all();
    }
}
