<?php

namespace App\Http\Controllers;

use App\DAO\BairrosDAO;
use App\DAO\CidadesDAO;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Util\PetraOpcaoFiltro;
use App\Util\PetraInjetorFiltro;
use Validator;
use Session;

class BairrosController extends Controller
{
    protected $dao;
    protected $cidadesdao;

    // Injeta o DAO no construtor
    public function __construct(BairrosDAO $dao, CidadesDAO $cidadesdao)
    {
      //$this->middleware('auth')->except('ajaxbairrosporcidade');
      $this->dao = $dao;
      $this->cidadesdao = $cidadesdao;
    }

    private function getCidades(){
      return $this->cidadesdao->all(0);
    }

    // GET /bairros
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
        return view("bairros.index")
          ->with('model',$model)
          ->with('query',$query)
          ->with('pesquisa',$this->dao->getCamposPesquisa());
    }

    // GET /bairros/create
    // Chama form de inclusão
    public function create()
    {
        // Controle de postback
        $model = Session::get('model', null);

        $model = $model ? $model : $this->dao->novo();

        // o form de inclusão e edição são os mesmos
    		return view('bairros.edit')
          			->with('model',$model)
                ->with('cidades',$this->getCidades());
    }

    // GET /bairros/{id}/edit
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
      return view('bairros.edit')
              ->with('model',$model)
              ->with('cidades',$this->getCidades());
    }

    // POST /bairros
    // Inclusão e Edição utilizam POST, portanto vem para esse método
    // Descobre se é inclusão pela presença do "id"
    public function store(Request $request)
    {
        $editando = false;
        $id = $request->input('id');
        $editando = $id;
        $all = $request->all();

        // Aproveita somente os campos para gravação
        $all = $request->only(['nome','id_cidade']);
        if ($editando){
          $retorno = $this->dao->update($id,$all);
        } else {
          $retorno = $this->dao->insert($request->all());
        }

        if ($retorno->status == 200) {
          return redirect('bairros')->with('mensagem',$retorno->mensagem);
        } else {
            if ($editando) {
              return redirect()
                      ->route('bairros.edit', [$id])
                      ->with('model',(object)$request->all())
                      ->withErrors($retorno->errors);
            } else {
              return redirect()
                      ->route('bairros.create')
                      ->with('model',(object)$request->all())
                      ->withErrors($retorno->errors);
            }
        }
    }

    // GET /bairros/{id}/delete
    // Chamará o formulário para confirmação de deleção
    public function delete($id)
    {
        $model = $this->dao->getById($id);
        return view('bairros.delete')
                ->with('model',$model);
    }

    // DELETE/POST /cidades/{id}
    // Exclusão propriamente dita.
    public function destroy($id)
    {
        $retorno = $this->dao->delete($id);
        return redirect('bairros');
    }

    // Não serve para nada. Veja store
    public function update(Request $request, $id){}

    // Não está sendo utilizado
    public function show($id){}
}
// 'end_date' => Carbon::now()->addDays(10)
