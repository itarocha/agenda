<?php

namespace App\Http\Controllers;

use App\Model\PetraOpcaoFiltro;
use App\Util\PetraInjetorFiltro;
use App\Model\CidadesDAO;
use App\Util\PetraConsts;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Session;
use App\Cidade;

class CidadesController extends Controller
{
    protected $dao;

    // Injeta o DAO no construtor
    public function __construct(CidadesDAO $dao)
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
        return view("cidades.index")
          ->with('model',$model)
          ->with('query',$query)
          ->with('pesquisa',$this->dao->getCamposPesquisa())
          ->with('titulo','Listagem de Cidades');
    }

    // GET /cidades/create
    // Chama form de inclusão
    public function create()
    {
        // Controle de postback
        $titulo = Session::get('titulo', null);
        $model = Session::get('model', null);

    		$titulo = $titulo ?  $titulo : 'Nova Cidade';
        $model = $model ? $model : $this->dao->novo();

        // o form de inclusão e edição são os mesmos
    		return view('cidades.edit')
          			->with('model',$model)
                ->with('estados', PetraConsts::getEstados())
          			->with('titulo',$titulo);
    }

    // GET /cidades/{id}/edit
    // Chama form de exclusão
    public function edit($id)
    {
      // Controle de postback
      $titulo = Session::get('titulo', null);
      $model = Session::get('model', null);

      $titulo = $titulo ?  $titulo : 'Editar Cidade';
  		$model = $model ? $model : $this->dao->getById($id);

      if (is_null($model)){
        //return Response::json('Não encontrado...', 404);
        throw new NotFoundHttpException;
      }
      // o form de inclusão e edição são os mesmos
      return view('cidades.edit')
              ->with('model',$model)
              ->with('estados',PetraConsts::getEstados())
              ->with('titulo',$titulo);
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

        // Aproveita somente os campos para gravação
        $all = $request->only(['nome','uf']);
        if ($editando){
          $retorno = $this->dao->update($id,$all);
        } else {
          $retorno = $this->dao->insert($request->all());
        }

        if ($retorno->status == 200) {
          return redirect('cidades')->with('mensagem',$retorno->mensagem);
        } else {
            if ($editando) {
              return redirect()
                      ->route('cidades.edit', [$id])
                      ->with('model',(object)$request->all())
                      ->with('titulo','Editar Cidade')
                      ->withErrors($retorno);
            } else {
              return redirect()
                      ->route('cidades.create')
                      ->with('model',(object)$request->all())
                      ->withErrors($retorno)
                      ->with('titulo','Nova Cidade');
            }
        }
    }

    // GET /cidades/{id}/delete
    // Chamará o formulário para confirmação de deleção
    public function delete($id)
    {
        $titulo = 'Confirma Exclusão de Cidade?';
        $model = $this->dao->getById($id);
        return view('cidades.delete')
                ->with('model',$model)
                ->with('titulo',$titulo);
    }

    // DELETE/POST /cidades/{id}
    // Exclusão propriamente dita.
    public function destroy($id)
    {
        $retorno = $this->dao->delete($id);
        return redirect('cidades');
    }

    // Não serve para nada. Veja store
    public function update(Request $request, $id){}

    // Não está sendo utilizado
    public function show($id){}

}
// 'end_date' => Carbon::now()->addDays(10)
