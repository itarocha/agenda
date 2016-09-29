<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\Model;

use DB;
use Auth;
use Laravel\Database\Exception;
use Carbon;
use App\Cidade;
use App\ModelValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;


class CidadesDAO extends AbstractDAO {

  private $app;

  function model(){
    return 'App\Cidade';
  }

  public function getCamposPesquisa(){
    return array(
        (object)array('name' => 'nome', 'type' => 'text', 'display' => 'Cidade'),
        (object)array('name' => 'uf', 'type' => 'text', 'display' => 'UF' ),
        );
  }

  public function getListagem(PetraOpcaoFiltro $q, $porPagina = 10){
    // REFATORAR
    $query = DB::table('cidades as tb')
              ->select( 'tb.id', 'tb.nome', 'tb.uf')
              ->orderBy('tb.nome');

    if (($q != null) && ($q->valido))
    {
      if ($q->op == "like")
      {
        $query->where('tb.'.$q->campo,"like","%".$q->valor_principal."%");
      } else
      if ($q->op == "between")
      {
         $query->whereBetween('tb.'.$q->campo,[$q->valor_principal, $q->valor_complemento]);
      } else {
        $query->where('tb.'.$q->campo,$q->op,$q->valor_principal);
      }
    }

    if ( isset($porPagina) && ($porPagina > 0)){
        //$retorno = Cidade::orderBy('nome')->paginate($porPagina);
        $retorno = $query->paginate($porPagina);
    } else {
      $retorno = $query->get();
      //$retorno = Cidade::orderBy('nome')->get();
    }
    return $retorno;
  }

  public function novo(){
  		$retorno = array('id'=>0, 'nome'=>'','uf' => 'MG');
  		return (object)$retorno; // Retorna um new StdClass;
  }

}
