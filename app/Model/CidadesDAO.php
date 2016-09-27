<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\Model;

use DB;
use Auth;
use Laravel\Database\Exception;
use Carbon;
use App\Cidade;

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
    /*
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
    */

    if ( isset($porPagina) && ($porPagina > 0)){
        $retorno = Cidade::orderBy('nome')->paginate($porPagina);
        //$retorno = $query->paginate($porPagina);
    } else {
      //$retorno = $query->get();
      $retorno = Cidade::orderBy('nome')->get();
    }

    return $retorno;
  }

  public function novo(){
  		//$retorno = new StdClass; //array('id'=>0,'descricao'=>'');
  		//$retorno->id = 0;
  		//$retorno->descricao = '';
  		$retorno = array('id'=>0, 'nome'=>'','uf' => 'MG');
  		return (object)$retorno; // Retorna um new StdClass;
  }

  public function getById($id){
    /*
    REFATORADO

    $query = DB::table('cidades as tb')
              ->select('tb.id', 'tb.nome', 'tb.uf')
              ->where('tb.id','=',$id);
    $retorno = $query->first();
    return $retorno;
    */
    return Cidade::find($id);
  }

  public function insert($array){

    // $obj = new Cidade();
    // $v = new ModelValidator();
    //
    // if ($v->validate($new, $obj->getRules())){
    //   $obj->fill($new);
    //   $obj->save();
    // } else {
    //   dd($v->errors());
    // }



    try {
      $array['id_usuario_criacao'] = Auth::user()->id;
      $array['data_hora_criacao'] = Carbon\Carbon::now();
      $array['id_usuario_alteracao'] = Auth::user()->id;
      $array['data_hora_alteracao'] = Carbon\Carbon::now();

      $id = DB::table('cidades')->insertGetId($array);
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


    // $task = Task::findOrFail($id);
    // $this->validate($request, [
    //         'title' => 'required',
    //         'description' => 'required'
    //     ]);
    // $task->fill($input)->save();

    $model = $this->getById($id);

    if (!$model){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    try {
      $array['id_usuario_alteracao'] = Auth::user()->id;
      $array['data_hora_alteracao'] = Carbon\Carbon::now();

      $affected = DB::table('cidades')
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

    //$bear = Bear::find(1);
    //$bear->delete();


    $affected = DB::table('cidades')
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
