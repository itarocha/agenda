<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\Model;

use DB;
use Laravel\Database\Exception;
use App\Model\PetraOpcaoFiltro;
use App\Bairro;
use App\Cidade;

class BairrosDAO extends AbstractDAO {

  function model(){
    return 'App\Bairro';
  }

  public function getCamposPesquisa(){
    return array(
        (object)array('name' => 'bairros.nome', 'type' => 'text', 'display' => 'Bairro'),
        (object)array('name' => 'cidades.nome', 'type' => 'text', 'display' => 'Cidade' ),
        (object)array('name' => 'cidades.uf', 'type' => 'text', 'display' => 'UF' ),
        );
  }

  public function novo(){
    		$retorno = array('id'=>0, 'nome'=>'','id_cidade' => null);
    		return (object)$retorno;
  }

  private function query(){
    $query = Bairro::join('cidades','cidades.id','=','bairros.id_cidade')
              ->select( 'bairros.id',
                        'bairros.nome',
                        'bairros.id_cidade',
                        'cidades.nome as cidade_nome',
                        'cidades.uf as cidade_uf')
              ->orderBy('bairros.nome');

    return $query;
  }

  public function getListagem(PetraOpcaoFiltro $q, $porPagina = 10){
    //dd($bairros->toJson());
    //dd($bairros);
    $query = $this->query();

    // montagem de pesquisa
    if (($q != null) && ($q->valido))
    {
      if ($q->op == "like")
      {
        $query->where($q->campo,"like","%".$q->valor_principal."%");
      } else
      if ($q->op == "between")
      {
         $query->whereBetween($q->campo,[$q->valor_principal, $q->valor_complemento]);
      } else {
        $query->where($q->campo,$q->op,$q->valor_principal);
      }
    }

    if ( isset($porPagina) && ($porPagina > 0)){
        $retorno = $query->paginate($porPagina);
    } else {
      $retorno = $query->get();
    }

    return $retorno;
  }

  public function listagemPorCidade($id_cidade){
    $query = $this->query()->where('bairros.id_cidade','=',$id_cidade)
    $retorno = $query->get();
    return $retorno;
  }


}
