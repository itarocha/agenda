<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'cidades';
    protected $fillable = array('nome', 'uf');
    public $rules = array( 'nome' => 'required|min:3|max:64',
                              'uf' => 'required|min:2|max:2');

    public function bairros(){
      return $this->hasMany('App\Model\Bairro');
    }

    // public function getRules(){
    //   return array( 'nome' => 'required|min:3|max:64',
    //                 'uf' => 'required|min:2|max:2');
    // }

}
