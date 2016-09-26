@extends('layouts.tema')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registrar Novo Usuário</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmação de Senha</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('isAdmin') ? ' has-error' : '' }}">
                             <label for="isAdmin" class="col-md-4 control-label">Administrador?</label>

                             <div class="col-md-6">
                                 <input id="isAdmin" type="text" class="form-control" name="isAdmin" required>

                                 @if ($errors->has('isAdmin'))
                                     <span class="help-block">
                                         <strong>{{ $errors->first('isAdmin') }}</strong>
                                     </span>
                                 @endif
                             </div>
                         </div>

                         <div class="form-group{{ $errors->has('podeIncluir') ? ' has-error' : '' }}">
                              <label for="isAdmin" class="col-md-4 control-label">Pode incluir Contato?</label>

                              <div class="col-md-6">
                                  <input id="podeIncluir" type="text" class="form-control" name="podeIncluir" required>

                                  @if ($errors->has('podeIncluir'))
                                      <span class="help-block">
                                          <strong>{{ $errors->first('podeIncluir') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group{{ $errors->has('podeAlterar') ? ' has-error' : '' }}">
                               <label for="podeAlterar" class="col-md-4 control-label">Pode alterar Contato?</label>

                               <div class="col-md-6">
                                   <input id="podeAlterar" type="text" class="form-control" name="podeAlterar" required>

                                   @if ($errors->has('podeAlterar'))
                                       <span class="help-block">
                                           <strong>{{ $errors->first('podeAlterar') }}</strong>
                                       </span>
                                   @endif
                               </div>
                           </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Gravar
                                </button>
                                <a href="/usuarios" class="btn btn-default">Voltar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
