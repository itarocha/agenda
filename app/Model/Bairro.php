<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Validator;
use App\Model\Cidade;
use App\Model\Contato;


class Bairro extends Model
{
    protected $table = 'bairros';
    protected $fillable = array('nome', 'id_cidade');
    public $rules = array( 'nome' => 'required|min:3|max:64',
                           'id_cidade' => 'required');

    public function cidade(){
      return $this->belongsTo('App\Model\Cidade', 'id_cidade');
    }

    public function contatos(){
      return $this->hasMany('App\Model\Contato','id','id_bairro');
    }
}
