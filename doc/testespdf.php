
<?php



      //$model->setPath('custom/url');
      $view = view("contatos.imprimir")
        ->with('model',$model)
        ->with('query',$query)
        ->with('pesquisa',$this->dao->getCamposPesquisa())
        ->with('titulo','Listagem de Contatos')->render();

        //dd($view);

        $pdf = PDF::loadView($view);
        return $pdf->stream();




      $query = new PetraOpcaoFiltro();
      PetraInjetorFiltro::injeta($request, $query);

      //dd($query->getValorPrincipalFormatado());

      $model = $this->dao->listagemComFiltro($query);
      // Carrega parâmetros do get (query params)
      // foreach ($request->query as $key => $value){
      //    $model->appends([$key => $value]);
      // }

      $view =  View::make('contatos.index', ['model' => $model])->render();

      //$model->setPath('custom/url');
      // return view("contatos.index")
      //   ->with('model',$model)
      //   ->with('query',$query)

      $pdf = PDF::loadView($view);
      return $pdf->stream();

      //return $pdf->download('invoice.pdf');


      $pdf = PDF::loadView('contatos.index', $model);
      return $pdf->download('invoice.pdf');

      //return PDF::loadFile(public_path().'/myfile.html')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

      // PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile.pdf')

?>
