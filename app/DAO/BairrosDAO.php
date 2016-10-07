<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\DAO;

use DB;
use Laravel\Database\Exception;
use App\Model\Bairro;
use App\Model\Cidade;

class BairrosDAO extends AbstractDAO {

  function model(){
    return 'App\Model\Bairro';
  }

  // Implementação de abstract
  // Essa query é repetida em listagem e getById
  function query(){
    $query = Bairro::join('cidades','cidades.id','=','bairros.id_cidade')
              ->select( 'bairros.id',
                        'bairros.nome',
                        'bairros.id_cidade',
                        'cidades.nome as cidade_nome',
                        'cidades.uf as cidade_uf')
              ->orderBy('bairros.nome');

    return $query;
  }


  public function getCamposPesquisa(){
    return array(
        (object)array('name' => 'bairros.nome', 'type' => 'text', 'display' => 'Bairro'),
        (object)array('name' => 'cidades.nome', 'type' => 'text', 'display' => 'Cidade' ),
        (object)array('name' => 'cidades.uf', 'type' => 'text', 'display' => 'UF' ),
        );
  }

  public function novo(){
    return (object)array('id'=>0, 'nome'=>'','id_cidade' => null);
  }

  public function listagemPorCidade($id_cidade){
    $query = $this->query()->where('bairros.id_cidade','=',$id_cidade);
    $retorno = $query->get();
    return $retorno;
  }


}
