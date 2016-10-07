<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\DAO;

use DB;
use Laravel\Database\Exception;
use App\Util\PetraOpcaoFiltro;

class UsuariosDAO {


  public function __construct(){
  }

  public function getRulesInsert(){

    return array( 'name' => 'required|max:255',
                  'email' => 'required|email|max:255|unique:users',
                  'password' => 'required|min:6|confirmed',);
  }

  public function getRulesEdit(){

    return array( 'name' => 'required|max:255',
                  'email' => 'required|email|max:255',);
  }
  // public function getCamposPesquisa(){
  //   return array(
  //       (object)array('name' => 'nome', 'type' => 'text', 'display' => 'Cidade'),
  //       (object)array('name' => 'uf', 'type' => 'text', 'display' => 'UF' ),
  //       );
  // }

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
    $query = DB::table('users as tb')
              ->select( 'tb.id', 'tb.name', 'tb.email', 'tb.password', 'tb.isAdmin', 'tb.podeIncluir', 'tb.podeAlterar')
              ->orderBy('tb.name');

    if ( isset($porPagina) && ($porPagina > 0)){
        $retorno = $query->paginate($porPagina);
    } else {
      $retorno = $query->get();
    }

    return $retorno;
  }

  public function novo(){
  		//$retorno = new StdClass; //array('id'=>0,'descricao'=>'');
  		//$retorno->id = 0;
  		//$retorno->descricao = '';
  		$retorno = array( 'id'=>0,
                        'name' => '',
                        'email' => '',
                        'isAdmin'=>'N',
                        'podeIncluir'=>'S',
                        'podeAlterar'=>'S');
  		return (object)$retorno; // Retorna um new StdClass;
  }

  public function getById($id){
    $query = DB::table('users as tb')
              ->select( 'tb.id', 'tb.name', 'tb.email', 'tb.password', 'tb.isAdmin',
                        'tb.podeAlterar', 'tb.podeIncluir')
              ->where('tb.id','=',$id);
    // Retorna apenas um registro. Se não encontra, retorna null
    $retorno = $query->first();
    return $retorno;
  }

  public function insert($array){
    try {
      $id = DB::table('users')->insertGetId($array);
      return (object)array( 'id' => $id,
                            'status' => 200,
                            'mensagem' => 'Criado com sucesso');
    } catch (\Exception $e){
      return (object)array( 'id' => -1,
                            'status' => 500,
                            'mensagem' => $e->getMessage());
    }
  }

  public function update($id, $array){
    $model = $this->getById($id);

    if (!$model){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    try {
      $affected = DB::table('users')
                    ->where('id',$id)
                    ->update($array);
      $retorno = ($affected == 1) ? 200 : 204;
      if ($affected == 1) {
        return (object)array(   'status'=>200,
                                'mensagem'=>'Alterado com sucesso');
      } else {
          return (object)array( 'status'=>204,
                                'mensagem'=>'Registro não necessita ser modificado');
      }
    } catch (\Exception $e) {
        //Campo inválido, erro de sintaxe
        return (object)array('status'=>500,
            'mensagem'=>'Falha ao alterar registro. Erro de sintaxe ou violação de chave'
            .$e->getMessage());
    }
    return $retorno;
  }

  public function delete($id)
  {
    $affected = DB::table('users')
                ->where('id',$id)
                ->delete();
    if ($affected == 1) {
      return (object)array( 'status'=>200,
                            'mensagem'=>'Excluído com sucesso');
    } else {
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
  }
}
