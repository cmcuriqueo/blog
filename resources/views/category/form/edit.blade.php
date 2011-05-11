@extends('layouts.app')

@section('title', 'Modificar: '.$category->name )

@section('content')
<div class="container">
	<div class="row">
		<h1>Editar Categoria</h1>

		<form method="POST" action="/categories/{{ $category->id }}">

			<input name="_token" type="hidden" value="{{ csrf_token() }}" />

			{{ method_field('PATCH') }}
			
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<input name="name" class="form-control" value="{{ $category->name }}"/>
				@if ($errors->has('name'))
                    <span class="help-block">
                 	    <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Actualizar Categoria</button>
				<a href="/categories" class="btn btn-default">Cancelar</a>
			</div>
			@if(Session::has('success'))
				@include('layouts.push')
			@endif
		</form>
	</div>
</div>
@endsection