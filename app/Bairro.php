<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use App\Cidade;
use App\Contato;


class Bairro extends Model
{
    protected $table = 'bairros';
    protected $fillable = array('nome', 'id_cidade');
    public $rules = array( 'nome' => 'required|min:3|max:64',
                           'id_cidade' => 'required');

    public function cidade(){
      return $this->belongsTo('App\Cidade', 'id_cidade');
    }

    public function contatos(){
      return $this->hasMany('App\Contato','id','id_bairro');
    }
}
