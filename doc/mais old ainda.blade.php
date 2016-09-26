@extends('layouts.tema')
@section('content')


<script>
$(function(){
  $(".btn-modal").click(function(){
    //alert("testando uou");
    $("#myModal").modal('show');
  });
});
</script>


<div id="teste"></div>
<a href="/contatos/create" class="btn btn-primary">Novo Contato</a>
<div id="container-main" class="container-fluid">
<!-- <div class="row">
  </div> -->
@include('partials.pesquisa')

@if (count($model) > 0)
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
          <th>Contato</th>
          <th>Localização</th>
          <th>Situação</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
			@foreach($model as $item)
				<tr>
          <td>
            <h3 class="text-warning">{{ $item->nome }}</h3>
            <p class="info small">{{ date('d/m/Y', strtotime($item->data_nascimento)) }}</p>
          </td>
          <td><h4>{{ $item->nome_bairro }}</h4>
            <p class="info small">{{ $item->nome_cidade }}-{{ $item->uf }}</p>
          </td>
          <td>
            @if($item->ligou == 'S')
            <p class="info small"> Ligou {{ date('d/m/Y', strtotime($item->data_hora_ligou)) }} - {{ $item->nome_usuario_ligou }}</p>
            @else
            <span class="label label-danger pull-down pull-down-right">Não Ligou</span>
            @endif
          </td>
					<td>
						<a href="/contatos/{{ $item->id }}/edit" class="btn btn-sm btn-default"><span class="text-info fa fa-edit fa-fw"></span> Editar</a>
						<a href="/contatos/{{ $item->id }}/delete" class="btn btn-sm btn-default"><span class="text-danger fa fa-trash-o fa-fw"></span> Excluir</a>
            @if($item->ligou == 'N')
            <a class="btn btn-sm btn-default" role="button" data-id="{{ $item->id }}" data-nome="{{ $item->nome }}"
               data-toggle="modal" data-target="#myModal"><span class="text-info fa fa-phone fa-fw"></span> Ligar</a>
            @endif
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
<div class="modal small fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="/contatos/ligar" method="post" class="form-horizontal" role="form"  id="frmLigar">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Confirmação</h3>
        </div>

        <div class="modal-body">
          {{ csrf_field() }}
          <input type="hidden" id="nome_contato" name="nome_contato">
          <input type="hidden" id="id_contato" name="id_contato">
          <p class="success-text">
            <i class="fa fa-phone modal-icon"></i>Confirma a ligação para
              <span class="modal-nome text-danger"></span><br>Essa tarefa não pode ser desfeita.
          </p>
        </div>

        <div class="modal-footer">
           <div class="pull-right">
              <button class="btn btn-success" id="btLigarConfirma"><i class="glyphicon glyphicon-ok"></i> Ligar</button>
              <button class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
           </div>
        </div>

      </div>
    </div>

  </form>
</div>


<script type="text/javascript">

$(function(){

    $("#myModal").on('show.bs.modal', function(event){
        // Get button that triggered the modal
        var button = $(event.relatedTarget);
        // Extract value from data-* attributes
        var _id = button.data('id');
        var _nome = button.data('nome');

        //alert(titleData);

        //$(this).find('.modal-title').text(titleData + ' Form');
        $(this).find('.modal-nome').text(_nome);
        $("#nome_contato").val(_nome);
        $("#id_contato").val(_id);
    });

     $('#btLigarConfirma').on('click', function(e){
        $( "#frmLigar" ).submit();
     });


});

</script>


@stop

<!-- <php echo $var->format('m/d/Y H:i'); > -->

<!--
show.bs.modal
This event fires immediately when the show instance method is called.

shown.bs.modal
This event is fired when the modal has been made visible to the user. It will wait until the CSS transition process has been fully completed before getting fired.

hide.bs.modal
This event is fired immediately when the hide instance method has been called.

hidden.bs.modal
This event is fired when the modal has finished being hidden from the user. It will wait until the CSS transition process has been fully completed before getting fired.

loaded.bs.modal
This event is fired when the modal has loaded content using the remote option.

-->
