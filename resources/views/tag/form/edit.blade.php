@extends('layouts.app')

@section('title', $tag->name )

@section('content')
<div class="container">
	<div class="row">
		<h1>Editar Tag</h1>

		<form method="POST" action="/tags/{{ $tag->id }}">

			<input name="_token" type="hidden" value="{{ csrf_token() }}" />

			{{ method_field('PATCH') }}
			
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<input name="name" class="form-control" value="{{ $tag->name }}"/>
				@if ($errors->has('name'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('name') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Actualizar Tag</button>
				<a href="/tags" class="btn btn-default">Cancelar</a>
			</div>
			@if(Session::has('success'))
				@include('layouts.push')
			@endif
		</form>
	</div>
</div>
@endsection