<?php

//https://bosnadev.com/2015/03/07/using-repository-pattern-in-laravel-5/#A_Repository_Implementation
// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\DAO;

use DB;
use Auth;
use Laravel\Database\Exception;
use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

use App\Util\ModelValidator;
use App\Util\PetraOpcaoFiltro;

abstract class AbstractDAO {

  private $app;
  private $query;


  abstract function model();
  abstract function getCamposPesquisa();
  //abstract function getListagem(PetraOpcaoFiltro $q, $porPagina = 10);
  abstract function query();
  abstract function novo();

  public function __construct(App $app){
    $this->app = $app;
    $this->makeModel();
    $this->query = $this->model->query();
  }

  private function makeModel(){
    $model = $this->app->make($this->model());

     if (!$model instanceof Model)
         throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

     return $this->model = $model;
  }

  public function getListagem(PetraOpcaoFiltro $q, $porPagina = 10){
    //dd($bairros->toJson());
    //dd($bairros);
    $query = $this->query();
    $query = $this->aplicaFiltro($query, $q); // AbstractDAO

    if ( isset($porPagina) && ($porPagina > 0)){
        $retorno = $query->paginate($porPagina);
    } else {
      $retorno = $query->get();
    }
    return $retorno;
  }

  public function aplicaFiltro($model, PetraOpcaoFiltro $q){
    if (($q != null) && ($q->valido))
    {
      if ($q->op == "like")
      {
        $model->where($q->campo,"like","%".$q->getValorPrincipalFormatado()."%");
      } else
      if ($q->op == "between")
      {
         $model->whereBetween($q->campo,[$q->getValorPrincipalFormatado(), $q->getValorComplementoFormatado()]);
      } else {
        $model->where($q->campo,$q->op,$q->getValorPrincipalFormatado());
      }
    }
    return $model;
  }

  public function all($porPagina = 10)
  {
    $q = new PetraOpcaoFiltro();
    return $this->getListagem($q, $porPagina);
  }

  public function listagemComFiltro(PetraOpcaoFiltro $q, $porPagina = 10)
  {
      return $this->getListagem($q, $porPagina);
  }

  public function getById($id){
    $model =  $this->model->find($id);
    return $model;
  }

  public function insert($array){
    $v = new ModelValidator();
    $model = $this->model;

    if ($v->validate($array, $model->rules)){
      $model->fill($array);
      $id = $model->save();
      return (object)array( 'id' => $model->id,
                            'status' => 200,
                            'mensagem' => 'Criado com sucesso');
    } else {
      return (object)array( 'id' => -1,
                            'status' => 500,
                            'mensagem' => "Erro ao gravar",
                            'errors' => $v->errors());
    }
  }

  public function update($id, $array){
    $model =  $this->model->find($id);
    if (!$model){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    $v = new ModelValidator();
    if ($v->validate($array, $model->rules)){
      $model->fill($array)->save();
      return (object)array(   'status'=>200,
                              'mensagem'=>'Alterado com sucesso');
    } else {
      return (object)array( 'status' => 500,
                            'mensagem' => "Erro ao gravar",
                            'errors' => $v->errors());
    }
  }

  public function delete($id)
  {
    $model =  $this->model->find($id);
    if (!$model){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    if ($model->delete()) {
      return (object)array( 'status'=>200,
                            'mensagem'=>'Excluído com sucesso');
    } else {
      return (object)array( 'status'=>500,
                            'mensagem'=>'Não foi possível excluir');
    }
  }
}
