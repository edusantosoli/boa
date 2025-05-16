<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaContabil extends Model
{
    protected $table = 'conta_contabils';
    protected $fillable = ['codigo', 'descricao'];
    use HasFactory;

    public function tiposServico()
        {
            return $this->hasMany(TipoServico::class, 'conta_contabil_id');
        } 




}
