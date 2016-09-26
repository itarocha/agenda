<!DOCTYPE html>
<html lang="pt-br">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ env('APP_TITLE') }}</title>
       <link href="/css/complemento.css" rel="stylesheet">

      <!-- Bootstrap -->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

      <link href="/css/starter-template.css" rel="stylesheet">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="/js/app.js"></script>

    </head>
  <body>

    <div class="container">
      <div>
        <h2>{{ isset($titulo) ? $titulo : 'Bem-Vindo' }}</h2>
      </div>

      @if(count($errors) > 0 )
      <div class="row">
          <div class="col-md-12 alert alert-danger" role="alert">
                 @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
          </div>
      </div>
      @endif

      @yield('content')

    </div><!-- /.container -->

    <!-- <footer class="bs-docs-footer" role="contentinfo">
        <div class="row">
            <div class="col-sd-8">
                Laboratório Linx - Sistemas para Tudo<br/>
                Rua Beleza dos Santos Granero, 5000 . Jardim Patrícia . Uberlândia MG . CEP 38230-511<br/>
                Tel.: (34) 3230 1520 . Fax: (34) 3251 0766
            </div>
        </div>
    </footer> -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


</body>
</html>
