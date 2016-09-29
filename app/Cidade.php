<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';

    protected $fillable = array('nome', 'uf');

    public function bairros(){
      return $this->hasMany('App\Bairro');
    }

    public function getRules(){
      return array( 'nome' => 'required|min:3|max:64',
                    'uf' => 'required|min:2|max:2');
    }

}
