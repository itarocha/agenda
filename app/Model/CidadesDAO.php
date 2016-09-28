<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\Model;

use DB;
use Auth;
use Laravel\Database\Exception;
use Carbon;
use App\Cidade;
use App\ModelValidator;

class CidadesDAO {

  protected $_estados;

  public function getRules(){
    return array( 'nome' => 'required|min:3|max:64',
                  'uf' => 'required|min:2|max:2');
  }

  public function getCamposPesquisa(){
    return array(
        (object)array('name' => 'nome', 'type' => 'text', 'display' => 'Cidade'),
        (object)array('name' => 'uf', 'type' => 'text', 'display' => 'UF' ),
        );
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

  private function getListagem(PetraOpcaoFiltro $q, $porPagina = 10){
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

  public function getById($id){
    return Cidade::find($id);
  }

  public function insert($array){
    $v = new ModelValidator();
    $obj = new Cidade();

    if ($v->validate($array, $obj->getRules())){
      $obj->fill($array);
      $id = $obj->save();
      return (object)array( 'id' => $obj->id,
                            'status' => 200,
                            'mensagem' => 'Criado com sucesso');
    } else {
      return (object)array( 'id' => -1,
                            'status' => 500,
                            'mensagem' => "Erro ao gravar",
                            'errors' => $v->errors(),
                          );
    }
  }

  public function update($id, $array){
    $obj = Cidade::find($id);
    if (!$obj){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    $v = new ModelValidator();
    if ($v->validate($array, $obj->getRules())){
      $obj->fill($array)->save();
      return (object)array(   'status'=>200,
                              'mensagem'=>'Alterado com sucesso');
    } else {
      return (object)array( 'status' => 500,
                            'mensagem' => "Erro ao gravar",
                            'errors' => $v->errors(),
                          );
    }
  }


  public function delete($id)
  {
    $model = Cidade::find($id);
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
