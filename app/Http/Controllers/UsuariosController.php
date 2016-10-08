<?php

namespace App\Http\Controllers;

use Validator;
use Session;

use App\Util\PetraOpcaoFiltro;
use App\Util\PetraInjetorFiltro;
use App\DAO\UsuariosDAO;

use Illuminate\Http\Request;
use App\Http\Requests;

class UsuariosController extends Controller
{
    protected $dao;

    // Injeta o DAO no construtor
    public function __construct(UsuariosDAO $dao)
    {
        $this->middleware('auth');
        $this->dao = $dao;
    }

    // GET /cidades
    public function index(Request $request)
    {
        // Consulta
        $query = new PetraOpcaoFiltro();
        PetraInjetorFiltro::injeta($request, $query);

        $model = $this->dao->listagemComFiltro($query);
        // Carrega parâmetros do get (query params)
        foreach ($request->query as $key => $value){
           $model->appends([$key => $value]);
        }

        //$model->setPath('custom/url');
        return view("usuarios.index")
          ->with('model',$model)
          //->with('pesquisa',$this->dao->getCamposPesquisa())
          ->with('query',$query);
    }

    // GET /cidades/create
    // Chama form de inclusão
    public function create()
    {
        // Controle de postback
        $model = Session::get('model', null);

        $model = $model ? $model : $this->dao->novo();

        // o form de inclusão e edição são os mesmos
    		return view('usuarios.edit')
          			->with('model',$model);
    }

    // GET /cidades/{id}/edit
    // Chama form de exclusão
    public function edit($id)
    {
      // Controle de postback
      $model = Session::get('model', null);

  		$model = $model ? $model : $this->dao->getById($id);

      if (is_null($model)){
        //return Response::json('Não encontrado...', 404);
        throw new NotFoundHttpException;
      }
      // o form de inclusão e edição são os mesmos
      return view('usuarios.edit')
              ->with('model',$model);
    }

    // POST /cidades
    // Inclusão e Edição utilizam POST, portanto vem para esse método
    // Descobre se é inclusão pela presença do "id"
    public function store(Request $request)
    {
        $editando = false;
        $id = $request->input('id');
        $editando = $id;
        $all = $request->all();
        // Valida campos apartir das regras estabelecidas no DAO injetado

        if ($editando){
          $validator = Validator::make($all, $this->dao->getRulesEdit());
        } else {
          $validator = Validator::make($all, $this->dao->getRulesInsert());
        }

        //dd($all);


        if ($validator->fails()){
          $model = (object)$all;
          if ($editando) {
            return redirect()
                    ->route('usuarios.edit', [$id])
                    ->with('model',$model)
                    ->withErrors($validator);
          } else {
            return redirect()
                    ->route('usuarios.create')
                    ->with('model',$model)
                    ->withErrors($validator);
          }
        } // end validator.fails

        // Aproveita somente os campos para gravação
        if ($editando){
          $all = $request->only([ 'name','email','isAdmin',
                                  'podeIncluir','podeAlterar']);

          $retorno = $this->dao->update($id,$all);
        } else {
          $all = $request->only([ 'name','email','password','isAdmin',
                                  'podeIncluir','podeAlterar']);

          //dd($all);

          $all['password'] = bcrypt($all['password']);

          $retorno = $this->dao->insert($all);
        }
        return redirect('usuarios');
    }

    // GET /cidades/{id}/delete
    // Chamará o formulário para confirmação de deleção
    public function delete($id)
    {
        $model = $this->dao->getById($id);
        return view('usuarios.delete')
                ->with('model',$model);
    }

    // DELETE/POST /cidades/{id}
    // Exclusão propriamente dita.
    public function destroy($id)
    {
        $retorno = $this->dao->delete($id);
        return redirect('usuarios');
    }

    // Não serve para nada. Veja store
    public function update(Request $request, $id){}

    // Não está sendo utilizado
    public function show($id){}

}
// 'end_date' => Carbon::now()->addDays(10)
