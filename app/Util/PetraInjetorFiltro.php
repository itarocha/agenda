<?php

namespace App\Util;

use Illuminate\Http\Request;
use App\Util\PetraOpcaoFiltro;

class PetraInjetorFiltro
{
    public static function injeta(Request $request, PetraOpcaoFiltro $filtro)
    {
      if ($request->input('q_campo') && $request->input('q_operador') &&
          $request->input('q_valor_principal') )
      {
        $filtro->campo = $request->input('q_campo');
        $filtro->tipo = $request->input('q_tipo');
        $filtro->operador = $request->input('q_operador');
        $filtro->valor_principal = $request->input('q_valor_principal');
        $filtro->valor_complemento = $request->input('q_valor_complemento'); // por enquanto

        $filtro->op =  ($filtro->operador == 'igual') ? '=' :
                       (($filtro->operador == 'diferente') ? '<>' :
                       (($filtro->operador == 'semelhante') ? 'like' :
                       (($filtro->operador == 'entre') ? 'between' : null )));

        $filtro->valido = ($filtro->op != null);
      }
    }

}
