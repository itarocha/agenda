<?php

namespace App\Util;

use Illuminate\Http\Request;
//use Illuminate\Queue\SerializesModels;

use Carbon;

class PetraOpcaoFiltro
{
    //use SerializesModels;

    public $campo;

    public $tipo;

    public $operador;

    public $op;

    public $valor_principal;

    public $valor_complemento;

    public $valido = false;

    public function __construct()
    {

    }

    public function getValorPrincipalFormatado()
    {
        if ($this->tipo == 'date'){
          return $this->converteData($this->valor_principal);
        } else {
          return $this->valor_principal;
        }
    }

    public function getValorComplementoFormatado()
    {
      if ($this->tipo == 'date'){
        return $this->converteData($this->valor_complemento);
      } else {
        return $this->valor_complemento;
      }
    }

    private function converteData($dta){
      return Carbon\Carbon::createFromFormat('d/m/Y', $dta )->toDateString();
    }

}
