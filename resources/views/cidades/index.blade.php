@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	Listagem de Cidades
@stop

<!-- Barra de pesquisa. Obrigatório apenas em páginas index -->
@section('pesquisa')
	@include('partials.pesquisa')
@stop

@section('content')

<div class="container-fluid">
		<div class="row">
			<a href="/cidades/create" class="btn btn-primary">Nova Cidade</a>
		</div>

		<div>&nbsp;</div>

		@if (count($model) > 0)
		<div class="row">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
			      <th>Nome</th>
			      <th>UF</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
				@foreach($model as $item)
					<tr>
			      <td>{{ $item->nome }}</td>
			      <td>{{ $item->uf }}</td>
						<td>
							<a href="/cidades/{{ $item->id }}/edit" class="btn btn-sm btn-default">
								<span class="text-info fa fa-edit fa-fw"></span> Editar</a>
							<a href="/cidades/{{ $item->id }}/delete" class="btn btn-sm btn-default">
								<span class="text-danger fa fa-trash-o fa-fw"></span> Excluir</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			{!! $model->links() !!}
		</div>
		@else
		<div class="row">
		    <div class="col-md-12 alert alert-warning" role="alert">
		      <p>Nenhum registro encontrado</p>
		    </div>
		</div>
		@endif
</div>

@stop
