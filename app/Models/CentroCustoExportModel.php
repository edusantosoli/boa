<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCustoExportModel extends Model
{
    protected $table = 'centros_de_custo'; // Aponta para a mesma tabela
    protected $fillable = ['codigo', 'descricao'];
    use HasFactory;
}
