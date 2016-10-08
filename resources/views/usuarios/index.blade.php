@extends('layouts.tema')

<!-- Título da página -->
@section('titulo')
	Listagem de Usuários
@stop

<!-- Barra de pesquisa. Obrigatório apenas em páginas index -->
@section('pesquisa')

@stop

@section('content')

<!-- usuarios.index -->

<div class="container-fluid">

	<div class="row">
		<a href="/usuarios/create" class="btn btn-primary">Novo Usuário</a>
	</div>

	<div>&nbsp;</div>

	@if (count($model) > 0)
	<div class="row">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
	          <th>Nome</th>
						<th>Email</th>
						<th>Administrador?</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
				@foreach($model as $item)
					<tr>
	          <td>{{ $item->name }}</td>
						<td>{{ $item->email }}</td>
						<td>{{ $item->isAdmin }}</td>
						<td>
							<a href="/usuarios/{{ $item->id }}/edit" class="btn btn-sm btn-default">
								<span class="text-info fa fa-edit fa-fw"></span> Editar</a>
							<a href="/usuarios/{{ $item->id }}/delete" class="btn btn-sm btn-default">
								<span class="text-danger fa fa-trash-o fa-fw"></span> Excluir</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
	{!! $model->links() !!}
	@else
	<div class="row">
	    <div class="col-md-12 alert alert-warning" role="alert">
	      <p>Nenhum registro encontrado</p>
	    </div>
	</div>

	@endif
	</div>
</div>

@stop

<!-- <php echo $var->format('m/d/Y H:i'); > -->
