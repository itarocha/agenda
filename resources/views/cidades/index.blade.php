@extends('layouts.tema')
@section('content')

<!-- cidades.index -->

<a href="/cidades/create" class="btn btn-primary">Nova Cidade</a>

<div class="container-fluid">
<!-- <div class="row">
  </div> -->
@include('partials.pesquisa')

@if (count($model) > 0)
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
						<a href="/cidades/{{ $item->id }}/edit" class="btn btn-sm btn-default"><span class="text-info fa fa-edit fa-fw"></span> Editar</a>
						<a href="/cidades/{{ $item->id }}/delete" class="btn btn-sm btn-default"><span class="text-danger fa fa-trash-o fa-fw"></span> Excluir</a>
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
