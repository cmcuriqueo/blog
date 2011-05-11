@extends('layouts.app')

@section('title', 'Crear Articulo' )

@section('styles')
    <link href="{{ asset('cropper/dist/cropper.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h1 class="text-center">Crear Articulo</h1>

		<form method="POST" action="/articles">

			<input name="_token" type="hidden" value="{{ csrf_token() }}" />
			
			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				<label for="title">Titulo</label><br>
				<input name="title" class="form-control" value="{{ old('title') }}"/>
				@if ($errors->has('title'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('title') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
			<label for="description">Description</label><br>
				<textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
				@if ($errors->has('description'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('description') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
				<textarea id="content" name="content">{{ old('content') }}</textarea>
				@if ($errors->has('content'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('content') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
				<label for="category_id">Categoria</label>
				<!--
				<input class="form-control" type="text" name="buscar_categoria" id="buscar_categoria"><br>
				-->
				<select id="categorias" class="form-control" name="category_id">
					<option value="0">Seleccion una Categoria</option>
					@foreach ($categories as $category)
					<option value="{{ $category->id }}">{{ $category->name }}</option>}
					@endforeach
				</select>
				@if ($errors->has('category_id'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('category_id') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
				<label for="tags[]">Tags</label>
				<select name="tags[]" class="form-control" multiple>
					@foreach ($tags as $tag)
					<option value="{{ $tag->id }}">{{ $tag->name }}</option>
					@endforeach
				</select>
				@if ($errors->has('tags'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('tags') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group{{ $errors->has('public') ? ' has-error' : '' }}">		
				<select class="form-control" name="public">
					<option value="0">Seguir Editando</option>
					<option value="1">Publicar</option>
				</select>
				@if ($errors->has('public'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('public') }}</strong>
	                </span>
	            @endif
			</div>

			<div class="form-group">
				<div class="text-center">
					<button type="submit" class="btn btn-primary">Publicar</button>
					<a href="/articles" class="btn btn-default">Cancelar</a>
				</div>
			</div>
			@if(Session::has('success'))
				@include('layouts.push')
			@endif
		</form>
	</div>
</div>
@endsection

@section('script')
		<script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
		<script type="text/javascript">
			tinymce.init({
				selector: '#content',
				plugins: [ 
				"advlist autolink lists link image imagetools charmap print preview anchor",
				"searchreplace  visualblocks code emoticons",
				"insertdatetime media table contextmenu paste imagetools"
				],
				content_css: '{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}'
			});
		</script>
		<!--
		<script src=" asset('js/jquery/dist/jquery.min.js') "></script>
		<script  type="text/javascript"  async defer>
			$("input[name='buscar_categoria']").change(function(){
				var buscar_categoria = $(this).val();
				var token = $("input[name='_token']").val();
				$.ajax({
					url: '/categories_select',
					method: 'POST',
					data: {buscar_categoria:buscar_categoria,_token},
					success: function(data) {
						$("select[name='categorias']").html('');
						$("select[name='categorias']").html(data.options);
					}
				});	
			});
		</script>
		-->
@endsection