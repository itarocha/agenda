<?php

namespace App;

use Validator;

class Bairro extends Model
{
    protected $table = 'bairros';
    protected $fillable = array('nome', 'id_cidade');

    private $rules = array(
      'nome' => 'required|min:3|max:64',
      'id_cidade' => 'required',
    );

    public function cidade(){
      return $this->belongsTo('Cidade');
    }

    public function getRules(){
      return $this->rules;
    }

}
