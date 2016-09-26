<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';

    protected $fillable = array('nome', 'uf');

    public function bairros(){
      return $this->hasMany('Bairro');
    }

    public function contatos(){
      return $this->belongsToMany('Contato','id','id_bairro');
    }
}