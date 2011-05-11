@extends('layouts.app')

@section('title', 'Tags' )

@section('content')
<div class="container">
    <div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="col-md-6 col-md-offset-3">
			<form  method="POST">
				<div class="form-group">
					<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Buscar">			
				</div>
				<input name="_token" type="hidden" value="{{ csrf_token() }}" />
			</form>
		</div>
		<div class="col-md-1">
			<div class="load" id="loading" style="display: none;">
				<img src="{{ asset('storage/image/FhHRx.gif') }}" alt="">
			</div>
		</div>
		<br><br>
		<div class="panel panel-info">
		  	<div class="panel-heading">
		  		<div class="text-center">Todos los Tags</div>
		  	</div>
		  	<div class="panel-body">
				<div class="table-responsive" id="search">
					<table class="table table-hover" >
						@forelse ($tags as $tag)
							<tr>
								<td>
								<a href="/tags/{{ $tag->id }}" class="btn btn-link pull-left">{{ $tag->name }} </a>
								</td>
								@if((Auth::user()->id == $tag->user_id 
									|| Auth::user()->type != 'member') && $tag->id != 1)
								<td>
									<a href="/tags/{{ $tag->id }}/edit" class="btn btn-link pull-right"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
								</td>
								<td>
									<form id="delete" action="/tags/{{ $tag->id }}" method="POST">
										<input name="_token" type="hidden" value="{{ csrf_token() }}" />
										{{ method_field('DELETE') }}
										<button class="btn btn-link pull-right" type="submit"><span class='glyphicon glyphicon-trash'></span> Eliminar</button>
									</form>
								</td>
								@else
									<th colspan="2"></th>
								@endif
							</tr>
						@empty
							<tr>
								<td>No hay tags cargados!.</td>
							</tr>
						@endforelse
					</table>
				</div>
		 	</div>
		</div>
		@if(Auth::user())
			<hr>

			<h3>Añadir un nuevo tag</h3>

			<form method="POST" action="/tags" id="new">

				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					<input name="name" class="form-control" value="{{ old('name') }}"/>
					@if ($errors->has('name'))
	                    <span class="help-block">
	                 	    <strong>{{ $errors->first('name') }}</strong>
	                    </span>
	                @endif
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Agregar Tag</button>
				</div>

			</form>
			
			@if(Session::has('success'))
				@include('layouts.push')
			@endif
		@endif
	</div>
</div>
@endsection

@section('script')


<script type="text/javascript">

	$(document).ready(function(){
		$("input[name='nombre']").on('change paste keyup', function(){
			var search = $(this).val();
			if(search.length > 1)
			{ 
				var token = $("input[name='_token']").val();
				$("#search").load('/tags/search/', { _token: token, search: search },function(responseTxt, statusTxt, xhr){});
			} else {
				var token = $("input[name='_token']").val();
				$("#search").load('/tags/search/', { _token: token},function(responseTxt, statusTxt, xhr){});
			}

		});
	});
	$(document).ajaxStart(function(){
		$("#loading").show();
	});
	$(document).ajaxStop(function(){
		$("#loading").hide();
	});
</script>

@endsection