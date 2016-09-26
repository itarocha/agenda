<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VerificaPermissoes
{

    private $livres = array("home.index", "login", "logout",
      "cidades.index","cidades.edit","cidades.create", "cidades.store","cidades.delete",
      "bairros.index","bairros.edit","bairros.create", "bairros.store","bairros.delete",
      "contatos.index","contatos.ligar","contatos.imprimir"
      );
    private $administrativas = array( "register", "contatos.delete", "usuarios.index",
                                      "usuarios.create", "usuarios.edit", "usuarios.store", "usuarios.delete");
    private $opcionais = array("contatos.create", "contatos.edit", "contatos.store");

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

       // method = GET
       //dd("role is ".$role);

      //  $info = array( 'method'=>$request->method(),
      //                 'pathInfo'=>$request->pathInfo(),
      //                 'requestUri'=>$request->requestUri());

        $rota = $request->route();
        $acao = $rota->getAction();

        $permissoes = array();

        $permissoes = array_merge($permissoes, $this->livres);

        if (Auth::user()) {
            if (Auth::user()->isAdmin == 'S') {
                //$request->session()->flash('mensagem','Administrador' );
                //array_push(, $administrativas);
                $permissoes = array_merge($permissoes, $this->administrativas, $this->opcionais);
            } else {
              // $request->session()->flash('mensagem',
                  // ' podeAlterar = '.Auth::user()->podeAlterar.
                  // ' podeIncluir = '.Auth::user()->podeIncluir );

                  if (Auth::user()->podeIncluir == 'S'){
                    $permissoes = array_merge($permissoes, array('contatos.create'));
                  }
                  if (Auth::user()->podeAlterar == 'S'){
                    $permissoes = array_merge($permissoes, array('contatos.edit', 'contatos.store'));
                  }
            }
        }
        if(array_key_exists('as', $acao))
        {
          // Se fosse para mostrar
          //$request->session()->flash('mensagem','Ação "'.$acao['as'].'"' );

          if ( !in_array($acao['as'], $permissoes )  ){
            //return redirect('/home')->with('msgerro','ACESSO NÃO PERMITIDO PARA A AÇÃO '.$acao['as'] );
            //$request->session()->flash('msgerro','Ação "'.$acao['as'].'"' );
            return redirect('/home')->with('msgerro','Acesso não permitido para a ação '.$acao['as'] );

          }
        } else {
          //$request->session()->flash('mensagem','Livre acesso' );
        }

        return $next($request);
    }

}
