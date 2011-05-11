@extends('layouts.app')

@section('title', 'Editar Informacion' )

@section('content')
	<div class="container">
		<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">Cambiar informacion personal</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/user/{{ $user->name }}/">

                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                            <label for="descripcion" class="col-md-4 control-label">Descripci&oacute;n</label>

                            <div class="col-md-6">
                                <textarea id="descripcion" class="form-control" name="descripcion">{{ $perfil->descripcion }} </textarea>

                                @if ($errors->has('descripcion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('informacion') ? ' has-error' : '' }}">
                            <label for="informacion" class="col-md-4 control-label">Informaci&oacute;n</label>

                            <div class="col-md-6">
                                <textarea id="informacion" class="form-control" name="informacion">{{ $perfil->informacion }}</textarea>

                                @if ($errors->has('informacion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('informacion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                                <a class="btn btn-danger" href="/user/{{ $user->name }}/">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		</div>
	</div>

@endsection