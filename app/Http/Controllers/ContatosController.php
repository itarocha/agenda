<?php

namespace App\Http\Controllers;

use App\Model\ContatosDAO;
use App\Model\CidadesDAO;
use App\Model\PetraOpcaoFiltro;
use App\Util\PetraInjetorFiltro;

use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Session;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Carbon;
use PDF;
use App;
use View;

class ContatosController extends Controller
{
    protected $dao;
    protected $cidadesDAO;

    public function __construct(ContatosDAO $dao, CidadesDAO $cidadesdao)
    {
      //$this->middleware('auth')->except('ajaxbairrosporcidade');
      $this->dao = $dao;
      $this->cidadesDAO = $cidadesdao;
    }

    // GET /cidades
    public function index(Request $request)
    {
        // Consulta
        $query = new PetraOpcaoFiltro();
        PetraInjetorFiltro::injeta($request, $query);

        //dd($query->getValorPrincipalFormatado());

        $model = $this->dao->listagemComFiltro($query);
        // Carrega parâmetros do get (query params)
        foreach ($request->query as $key => $value){
           $model->appends([$key => $value]);
        }

        if ($request->input('q_print') == "S")
        {
          $pdf = PDF::loadView('contatos.imprimir',
                    [ 'model' => $model,
                      'query'=>$query,
                      'pesquisa'=>$this->dao->getCamposPesquisa(),
                      'titulo'=>'Listagem de Contatos'
                    ]);
          //return $pdf->stream();
          return $pdf->download('Contatos.pdf');
        }

        //$model->setPath('custom/url');
        return view("contatos.index")
          ->with('model',$model)
          ->with('query',$query)
          ->with('pesquisa',$this->dao->getCamposPesquisa())
          ->with('titulo','Listagem de Contatos');

    }

    // GET /cidades/create
    // Chama form de inclusão
    public function create(Request $request)
    {
      //dd(Auth::user());
       //dd($request->session());

        // Controle de postback
        $titulo = Session::get('titulo', null);
        $model = Session::get('model', null);

    		$titulo = $titulo ?  $titulo : 'Novo Contato';
        $model = $model ? $model : $this->dao->novo();


        //$cidadesDAO = new CidadesDAO();
        $cidades = $this->cidadesDAO->all(0);

        //dd($cidades);

        // o form de inclusão e edição são os mesmos
    		return view('contatos.edit')
          			->with('model',$model)
                ->with('cidades',$cidades)
          			->with('titulo',$titulo);
    }

    // GET /cidades/{id}/edit
    // Chama form de exclusão
    public function edit($id)
    {
      // Controle de postback
      $titulo = Session::get('titulo', null);
      $model = Session::get('model', null);

      $titulo = $titulo ?  $titulo : 'Editar Contato';
  		$model = $model ? $model : $this->dao->getById($id);

      if (is_null($model)){
        //return Response::json('Não encontrado...', 404);
        throw new NotFoundHttpException;
      }

      // mudar display da data de nascimento
      $model->data_nascimento = $model->data_nascimento ? date('d/m/Y',
                                        strtotime($model->data_nascimento)) : '';

      //$cidadesDAO = new CidadesDAO();
      $cidades = $this->cidadesDAO->all(0);

      // o form de inclusão e edição são os mesmos
      return view('contatos.edit')
              ->with('model',$model)
              ->with('cidades',$cidades)
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
        // Valida campos apartir das regras estabelecidas no DAO injetado


        //dd($all);
        //str_replace('_', ' ', $str)
        $all = $request->only([
          'nome',
          'data_nascimento',
          'cpf',
          'titulo',
          'secao',
          'zona',
          'endereco',
          'numero',
          'complemento',
          'id_bairro',
          'cep',
          'telefone1',
          'telefone2',
          'telefone3',
          'telefone4',
          'telefone5',
          'ligou',
        ]);

        $all['cpf'] = str_replace('.', '', $all['cpf']);
        $all['cpf'] = str_replace('-', '', $all['cpf']);
        $all['cep'] = str_replace('-', '', $all['cep']);

        //dd($all);

        $data_nascimento = $all['data_nascimento'] ? Carbon\Carbon::createFromFormat('d/m/Y', $all['data_nascimento'])->toDateString() : null;
        $all['data_nascimento'] = $data_nascimento;

        if ($editando){
          $retorno = $this->dao->update($id,$all);
        } else {
          $retorno = $this->dao->insert($all);
        }

        if ($retorno->status == 200) {
          return redirect('contatos')->with('mensagem',$retorno->mensagem);
        } else {
          //$data_nascimento = $all['data_nascimento'] ? Carbon\Carbon::createFromFormat('d/m/Y', $all['data_nascimento'])->toDateString() : null;
          //$all['data_nascimento'] = $data_nascimento;
          if ($editando) {
            return redirect()
                    ->route('contatos.edit', [$id])
                    ->with('model',(object)$request->all())
                    ->with('titulo','Editar Contato')
                    ->withErrors($retorno->errors);
          } else {
            return redirect()
                    ->route('contatos.create')
                    ->with('model',(object)$request->all())
                    ->withErrors($retorno->errors)
                    ->with('titulo','Novo Contato');
          }
        }

    }

    // GET /cidades/{id}/delete
    // Chamará o formulário para confirmação de deleção
    public function delete($id)
    {
        $titulo = 'Confirma Exclusão de Contato?';
        $model = $this->dao->getById($id);
        return view('contatos.delete')
                ->with('model',$model)
                ->with('titulo',$titulo);
    }

    // DELETE/POST /cidades/{id}
    // Exclusão propriamente dita.
    public function destroy($id)
    {
        $retorno = $this->dao->delete($id);
        return redirect('contatos');
    }

    // GET /contatos/ligar
    // Liga para o contato
    public function ligar(Request $request)
    {
      //dd('chamou contatos/ligar post');

      $nome_contato = $request->input('nome_contato');
      $id_contato = $request->input('id_contato');

      $retorno = $this->dao->ligar($id_contato);
      //dd($retorno);

      return redirect('contatos')->with('mensagem','Ligação efetuada com sucesso para ['.
      $nome_contato.'] ');
    }

    public function imprimir(Request $request){
      //$pdf = App::make('dompdf.wrapper');
      //$pdf->loadHTML('<h1>Test</h1>');
      //return $pdf->stream();
      //return $pdf->download('teste.pdf');

      $query = new PetraOpcaoFiltro();
      PetraInjetorFiltro::injeta($request, $query);

      //dd($query->getValorPrincipalFormatado());

      $model = $this->dao->listagemComFiltro($query, 0);
      // Carrega parâmetros do get (query params)
      foreach ($request->query as $key => $value){
         $model->appends([$key => $value]);
      }

      $pdf = PDF::loadView('contatos.imprimir',
                [ 'model' => $model,
                  'query'=>$query,
                  'pesquisa'=>$this->dao->getCamposPesquisa(),
                  'titulo'=>'Listagem de Contatos'
                ]);
      return $pdf->stream();
      //return $pdf->download('Contatos.pdf');
}

    // Não serve para nada. Veja store
    public function update(Request $request, $id){}

    // Não está sendo utilizado
    public function show($id){}
}
// 'end_date' => Carbon::now()->addDays(10)
