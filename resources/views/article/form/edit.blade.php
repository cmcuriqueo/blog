@extends('layouts.app')

@section('title', 'Modificar: '.$article->title )

@section('styles')
    <link href="{{ asset('cropper/dist/cropper.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h1 class="text-center">Editar Articulo</h1>

		<form method="POST" action="/articles/{{ $article->id }}">

			<input name="_token" type="hidden" value="{{ csrf_token() }}" />

			{{ method_field('PATCH') }}
			
			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				<label for="title">Titulo</label><br>
				<input name="title" class="form-control" value="{{ $article->title }}"/>
				@if ($errors->has('title'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('title') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
				<label for="description">Description</label><br>
				<textarea class="form-control" name="description">{{ $article->description }}</textarea>
				@if ($errors->has('description'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('description') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
				<textarea id="content" name="content">{{ $article->content }}</textarea>
				@if ($errors->has('content'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('content') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
				<select class="form-control" name="category_id">
					<option value="0">Seleccion una Categoria</option>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}" 
							{{ $article->category->id == $category->id ? ' selected' : '' }}>
							{{ $category->name }}
						</option>
					@endforeach
				</select>
				@if ($errors->has('category_id'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('category_id') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
				<select name="tags[]" class="form-control" multiple>
					@foreach ($tags as $tag)
						@foreach ($selectTags as $element)
							@if($element->id === $tag->id)
								<option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
								@break
							@endif
						@endforeach
						@if($element->id != $tag->id)
						<option value="{{ $tag->id }}">{{ $tag->name }}</option>
						@endif
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
					<option value="0"{{ $article->public == '0' ? ' selected' : '' }}>Seguir Editando</option>
					<option value="1"{{ $article->public == '1' ? ' selected' : '' }}>Publicar</option>
				</select>
				@if ($errors->has('public'))
	                <span class="help-block">
	            	    <strong>{{ $errors->first('public') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="form-group">
				<div class="text-center">
					<button type="submit" class="btn btn-primary">Actualizar</button>
					<a href="/articles" class="btn btn-default">Cancelar</a>
				</div>
			</div>
			@if(Session::has('success'))
				@include('layouts.push')
			@endif
		</form>
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
	</div>
</div>
@endsection