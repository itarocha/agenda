@extends('layouts.impressao')
@section('content')

<!-- <div class="row">
  </div> -->
@if (count($model) > 0)
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
          <th>Contato</th>
					<th>Localização</th>
					<th>Telefones</th>
          <th>Situação</th>
				</tr>
			</thead>
			<tbody>
			@foreach($model as $item)
				<tr>
          <td>
            <p class="info small">{{ $item->nome }}</p>
						<p class="info small">{{ date('d/m/Y', strtotime($item->data_nascimento)) }}</p>
          </td>
					<td><p class="info small">{{ $item->nome_bairro }}</p>
            <p class="info small">{{ $item->nome_cidade }}-{{ $item->uf }}</p>
          </td>
					<td>
            <p class="info small">
	            @if(!empty($item->telefone1))
	            {{ $item->telefone1." "}}<br/>
	            @endif
	            @if(!empty($item->telefone2))
	            {{ $item->telefone2." "}}<br/>
	            @endif
	            @if(!empty($item->telefone3))
	            {{ $item->telefone3." "}}<br/>
	            @endif
	            @if(!empty($item->telefone4))
	            {{ $item->telefone4." "}}<br/>
	            @endif
	            @if(!empty($item->telefone5))
	            {{ $item->telefone5." "}}<br/>
	            @endif
	            @if(!empty($item->telefone6))
	            {{ $item->telefone6." "}}<br/>
	            @endif
	          </p>
          </td>
          <td>
            @if($item->ligou == 'S')
            	<p class="info small">{{ $item->nome_usuario_ligou }} <br/>
								ligou em  {{ date('d/m/Y', strtotime($item->data_hora_ligou)) }} </p>
            @else
            	<p class="info small">Não Ligou</p>
            @endif
          </td>
				</tr>
			@endforeach
			</tbody>
		</table>
@else
<div class="row">
    <div class="col-md-12 alert alert-warning" role="alert">
      <p>Nenhum registro encontrado</p>
    </div>
</div>
@endif

@stop
