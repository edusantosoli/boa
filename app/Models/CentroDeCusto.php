<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroDeCusto extends Model
{
    protected $fillable = ['codigo', 'descricao'];
    protected $table = 'centros_de_custo';
    use HasFactory;

    public function contasContabeis()
    {
        return $this->belongsToMany(ContaContabil::class, 'centros_de_custo');
    }



}
