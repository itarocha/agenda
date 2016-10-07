<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Bairro;

class Contato extends Model
{
    protected $table = 'contatos';
    protected $fillable = array('id',
              'nome',
              'data_nascimento',
              'cpf',
              'titulo',
              'secao',
              'zona',
              'endereco',
              'numero',
              'complemento',
              'id_cidade',
              'id_bairro',
              'cep',
              'telefone1',
              'telefone2',
              'telefone3',
              'telefone4',
              'telefone5',
              'ligou',
              //'id_usuario_ligou',
              //'data_hora_ligou'
            );

    public $rules = array( 'nome' => 'required|min:3|max:64',
                            //'data_nascimento' => 'required|date_format:d/m/Y',
                            'data_nascimento' => 'required',
                            'cpf' => 'required|size:11',
                            'titulo' => 'max:32',
                            'secao' => 'max:8',
                            'zona' => 'max:8',
                            'endereco' => 'required|max:64',
                            'numero'  => 'max:8',
                            'complemento' => 'max:32',
                            'id_bairro' => 'required',
                            'cep' => 'size:8',
                            'telefone1' => 'max:16',
                            'telefone2' => 'max:16',
                            'telefone3' => 'max:16',
                            'telefone4' => 'max:16',
                            'telefone5' => 'max:16',
                          );

    public function bairro(){
      return $this->belongsTo('App\Model\Bairro', 'id_bairro');
    }
}
