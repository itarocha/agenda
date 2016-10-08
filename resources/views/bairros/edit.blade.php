@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	{{ $model->id == 0 ? 'Incluir Bairro' : 'Editar Bairro' }}
@stop

@section('content')

<script>
$(function(){
	$("#nome").focus();
});
</script>

<div class="container-fluid">
  <div class="row">

		<form action="/bairros" method="POST">
		   {{ csrf_field() }}
		   <input type="hidden" id="id" name="id" value="{{ $model->id }}">
		  <div class="row">
		    <div class="col-md-6">
		      <label for="nome" class="input-label f-left">Bairro:</label>
		      <input type="text" class="input-text f-left" id="nome" name="nome" value="{{ $model->nome }}">
		    </div>

		    <div class="col-md-6">
		      <label for="uf" class="input-label f-left">Cidade:</label>
		      <select class="input-text f-left" name="id_cidade" id="id_cidade">
		        @foreach($cidades as $cidade)
		          <option value="{{ $cidade->id }}"
								{{ $model->id_cidade == $cidade->id ? 'selected="selected"' : '' }}>
		            {{ $cidade->nome }} - {{ $cidade->uf }}
		          </option>
		        @endforeach
		      </select>
		    </div>
		  </div>

		  <div class="row">
		    <div class="col-md-12 pt-20">
		     <input type="submit" class="btn btn-success" value="Gravar">
		     <a href="/bairros" class="btn btn-default">Cancelar</a>
		    </div>
		  </div>
		</form>
	</div>
</div>
@stop
