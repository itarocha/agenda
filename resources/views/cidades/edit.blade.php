@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	{{ $model->id == 0 ? 'Incluir Cidade' : 'Editar Cidade' }}
@stop

@section('content')
<script>
$(function(){
	$("#nome").focus();
});
</script>

<div class="container-fluid">
  <div class="row">

		<form action="/cidades" method="POST">
		   {{ csrf_field() }}
		   <input type="hidden" id="id" name="id" value="{{ $model->id }}">
		  <div class="row">
		    <div class="col-md-8">
		      <label for="nome" class="input-label f-left">Cidade:</label>
		      <input type="text" class="input-text f-left" id="nome" name="nome" value="{{ $model->nome }}">
		    </div>

		    <div class="col-md-4">
		      <label for="uf" class="input-label f-left">UF:</label>
		      <select class="input-text f-left" name="uf" id="uf">
		        @foreach($estados as $estado)
		          <option value="{{ $estado->id }}" {{ $model->uf == $estado->id ? 'selected="selected"' : '' }}>
		            {{ $estado->nome }}
		          </option>
		        @endforeach
		      </select>
		    </div>
		  </div>

		  <div class="row">
		    <div class="col-md-12 pt-20">
		     <input type="submit" class="btn btn-success" value="Gravar">
		     <a href="/cidades" class="btn btn-default">Cancelar</a>
		    </div>
		  </div>
		</form>
	</div>
</div>
@stop
