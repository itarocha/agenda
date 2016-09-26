<!-- usuarios.edit -->
@extends('layouts.tema')
@section('content')
<!-- <script>
$(function(){
	$("#descricao").focus();
});
</script> -->
<form action="/usuarios/{{$model->id}}" method="POST">
  <input type="hidden" id="_method" name="_method" value="DELETE">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-md-12">
      <h3>{{$model->name}} - {{$model->email}}</h3>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 pt-20">
     <input type="submit" class="btn btn-danger" value="Excluir">
     <a href="/usuarios" class="btn btn-default">Desistir</a>
    </div>
  </div>
</form>
</div>
@stop
