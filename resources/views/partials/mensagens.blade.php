@if ( session('mensagem') )
<div class="alert alert-success" role="success">
  <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
  {{ session('mensagem') }}</div>
@endif
@if ( session('msgerro') )
<div class="alert alert-error" role="success">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  {{ session('msgerro') }}</div>
@endif

@if(count($errors) > 0 )
<div class="row">
    <div class="col-sm-6 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading no-collapse">Foram encontrados erros</div>
            <div class="panel-body">
              <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
        </div>
    </div>
</div>
@endif
