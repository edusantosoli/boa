<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServico extends Model
{
    protected $fillable = ['nome', 'conta_contabil_id'];

    //public function contaContabil()
    //{
     //   return $this->belongsTo(ContaContabil::class);
    //}

    public function contaContabil()
{
    return $this->belongsTo(ContaContabil::class, 'conta_contabil_id');
}
}

