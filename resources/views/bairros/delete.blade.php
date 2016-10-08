<!-- bairros.delete -->
@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	Deseja realmente excluir o Bairro abaixo?
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <form action="/bairros/{{$model->id}}" method="POST">
      <input type="hidden" id="_method" name="_method" value="DELETE">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-12">
          <h3>{{$model->nome}} - {{$model->cidade_nome}} - {{$model->uf}}</h3>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 pt-20">
         <input type="submit" class="btn btn-danger" value="Excluir">
         <a href="/bairros" class="btn btn-default">Desistir</a>
        </div>
      </div>
    </form>
  </div>
</div>

@stop
