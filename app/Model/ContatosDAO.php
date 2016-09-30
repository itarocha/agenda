<?php

// https://laravel.com/api/5.3/Illuminate/Database/Query/Builder.html
namespace App\Model;

use DB;
use Laravel\Database\Exception;
use Carbon;
use App\Model\PetraOpcaoFiltro;
use App\Bairro;
use App\Cidade;
use App\Contato;

class ContatosDAO extends AbstractDAO {

  function model(){
    return 'App\Contato';
  }

  public function getCamposPesquisa(){
    return array(
      (object)array('name' => 'contatos.nome', 'type' => 'text', 'display' => 'Nome'),
      (object)array('name' => 'contatos.cpf', 'type' => 'text', 'display' => 'CPF'),
      (object)array('name' => 'contatos.data_nascimento', 'type' => 'date', 'display' => 'Nascimento'),
      (object)array('name' => 'bairros.nome', 'type' => 'text', 'display' => 'Bairro'),
      (object)array('name' => 'cidades.nome', 'type' => 'text', 'display' => 'Cidade' ),
      (object)array('name' => 'cidades.uf', 'type' => 'text', 'display' => 'UF' ),
      (object)array('name' => 'contatos.titulo', 'type' => 'text', 'display' => 'Título' ),
      (object)array('name' => 'contatos.secao', 'type' => 'text', 'display' => 'Seção' ),
      (object)array('name' => 'contatos.zona', 'type' => 'text', 'display' => 'Zona' ),
      (object)array('name' => 'contatos.ligou', 'type' => 'text', 'display' => 'Ligou (S/N)' ),
      (object)array('name' => 'users.name', 'type' => 'text', 'display' => 'Usuário que ligou ' ),
        );
  }

  // Abstrato
  public function novo(){
  		$retorno = array('id'=>0,
                'nome'=>'',
                'data_nascimento'=>'',
                'cpf'=>'',
                'titulo'=>'',
                'secao'=>'',
                'zona'=>'',
                'endereco'=>'',
                'numero'=>'',
                'complemento'=>'',
                'id_cidade'=>'',
                'id_bairro'=>'',
                'cep'=>'',
                'telefone1'=>'',
                'telefone2'=>'',
                'telefone3'=>'',
                'telefone4'=>'',
                'telefone5'=>'',
                'ligou'=>'N',
                'id_usuario_ligou'=>'',
                'data_hora_ligou'=>'');
  		return (object)$retorno; // Retorna um new StdClass;
  }

  // Tornar abstrato
  function query(){
    $query = DB::table('contatos')
            ->select( 'contatos.id',
                      'contatos.nome',
                      'contatos.data_nascimento',
                      'contatos.cpf',
                      'contatos.titulo',
                      'contatos.secao',
                      'contatos.zona',
                      'contatos.endereco',
                      'contatos.numero',
                      'contatos.complemento',
                      'contatos.id_bairro',
                      'bairros.nome as nome_bairro',
                      'cidades.id as id_cidade',
                      'cidades.nome as nome_cidade',
                      'cidades.uf',
                      'contatos.cep',
                      'contatos.telefone1',
                      'contatos.telefone2',
                      'contatos.telefone3',
                      'contatos.telefone4',
                      'contatos.telefone5',
                      'contatos.ligou',
                      'contatos.id_usuario_ligou',
                      'users.name as nome_usuario_ligou',
                      'contatos.data_hora_ligou'
                      )
                      ->join('bairros','bairros.id','=','contatos.id_bairro')
                      ->join('cidades','cidades.id','=','bairros.id_cidade')
                      ->leftJoin('users', 'users.id', '=', 'contatos.id_usuario_ligou')
            ->orderBy('contatos.nome');
    return $query;
  }

  public function ligar($id){
    $model = $this->getById($id);
    $array = array();

    $array['id_usuario_ligou'] = Auth::user()->id;
    $array['data_hora_ligou'] = Carbon\Carbon::now();
    $array['ligou'] = 'S';

    if (!$model){
      return (object)array( 'status'=>404,
                            'mensagem'=>'Não encontrado');
    }
    try {
      $affected = DB::table('contatos')
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

}
