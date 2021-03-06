<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\DAO;

use DB;
use Auth;
use Laravel\Database\Exception;
use App\Model\Cidade;


class CidadesDAO extends AbstractDAO {

  function model(){
    return 'App\Model\Cidade';
  }

  function query(){
    return Cidade::select( 'id', 'nome', 'uf')->orderBy('nome');
  }

  public function getCamposPesquisa(){
    return array(
        (object)array('name' => 'nome', 'type' => 'text', 'display' => 'Cidade'),
        (object)array('name' => 'uf', 'type' => 'text', 'display' => 'UF' ),
        );
  }

  public function novo(){
  		return (object)array('id'=>0, 'nome'=>'','uf' => 'MG');
  }

}
