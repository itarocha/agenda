<?php

namespace App\Http\Controllers;

use App\Model\BairrosDAO;
use App\Model\CidadesDAO;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Session;

class AjaxController extends Controller
{
    // Injeta o DAO no construtor
    public function __construct()
    {
      //$this->middleware('auth')->except('ajaxbairrosporcidade');
    }

    public function bairrosporcidade(Request $request){
      $id_cidade = $request->input('id_cidade');
      $id_bairro = $request->input('id_bairro');

      $dao = new BairrosDAO();

      $lista = $dao->listagemPorCidade($id_cidade);


      // foreach($bairros as $bairro){
      //
      // }



      if (count($lista) > 0) {
          $retorno = '<option value="">Selecione uma opção ... </option>';


          foreach ($lista as $value) {
              $selected = $value->id == $id_bairro ? ' selected ="selected" ' : '';

              //$valor = $value->nome." ".$id_bairro;

              $retorno .= '<option value="' . $value->id . '" '.$selected.' >' . $value->nome .  '</option>';
          }


      } else {
          $retorno = '<option value="">Nenhum registro encontrado! </option>';
      }


      //dd($retorno);

      //$retorno = '<option value="">Achou...'.$id_cidade.'</option>';
      //$retorno = 'Itamar';
      return response()->json($retorno,200);
    }
}
// 'end_date' => Carbon::now()->addDays(10)
