<!-- bairros.delete -->
@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	Deseja realmente excluir o Contato abaixo?
@stop

@section('content')

<div class="container-fluid">
  <div class="row">
    <form action="/contatos/{{$model->id}}" method="POST">
      <input type="hidden" id="_method" name="_method" value="DELETE">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-12">
          <h3>{{$model->nome}}</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 pt-20">
         <input type="submit" class="btn btn-danger" value="Excluir">
         <a href="/contatos" class="btn btn-default">Desistir</a>
        </div>
      </div>
    </form>
  </div>
</div>
@stop
