<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;

    protected $table = 'conta_contabils'; // Nome exato da tabela no banco
    protected $fillable = ['codigo', 'descricao'];
}
