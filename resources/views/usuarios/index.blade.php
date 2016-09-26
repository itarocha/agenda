@extends('layouts.tema')
@section('content')

<!-- usuarios.index -->

<a href="/usuarios/create" class="btn btn-primary">Novo Usuário</a>

<div class="container-fluid">

@if (count($model) > 0)
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
						<a href="/usuarios/{{ $item->id }}/edit" class="btn btn-sm btn-default"><span class="text-info fa fa-edit fa-fw"></span> Editar</a>
						<a href="/usuarios/{{ $item->id }}/delete" class="btn btn-sm btn-default"><span class="text-danger fa fa-trash-o fa-fw"></span> Excluir</a>
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

@stop

<!-- <php echo $var->format('m/d/Y H:i'); > -->
