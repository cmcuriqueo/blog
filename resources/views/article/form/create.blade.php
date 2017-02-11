@extends('layouts.app')

@section('title', 'Crear Articulo' )

@section('styles')
    <link href="{{ asset('cropper/dist/cropper.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/fileinput/fileinput.css') }}" media="all" rel="stylesheet" type="text/css" />
    <style type="text/css">
    	.panel-body{
    		background-color: #fff;
    	}
    </style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6 col-md-offset-6">
				<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>&nbsp;Genere enlaces a imagenes: 
				<div class="panel panel-info">
					<div class="panel-body">
						<form id="imagen" method="POST" enctype="multipart/form-data">
							<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
								<input type="file" id="image" name="image"">
							</div>
							<div id="obtener-link" class="form-group">
								<button type="submit" class="btn btn-default">
									Obtener Link
								</button>
							</div>
						</form>
						<div style="display: none;" id="path">
							<div class="input-group">
								<input class="form-control" type="text" id="imagen-input" required>
								<span class="input-group-btn">
									<button id="btn-copy" type="button" class="btn btn-default" data-clipboard-action="copy" data-clipboard-target="#imagen-input" title="Copy">
										<span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>

			</div>
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
</div>
@endsection

@section('script')
		<script type="text/javascript" src="{{ asset('js/clipboard/dist/clipboard.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/fileinput/fileinput.min.js')  }}"></script>
		<script type="text/javascript" src="{{ asset('js/fileinput/locales/es.js') }}"></script>
		<script type="text/javascript">
			tinymce.init({
				selector: '#content',
				height: 1040,
				max_width: 90,
				language: 'es_AR',
				plugins: [ 
				"advlist autolink lists link image imagetools charmap print preview anchor",
				"searchreplace  visualblocks code emoticons",
				"insertdatetime table contextmenu paste imagetools"
				],
				content_css: '{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}'
			});
		</script>
		<script type="text/javascript">
			$("#image").fileinput({
				showUpload: false,
				showCaption: false,
				maxFileSize: 2024,
        		language: 'es',
				browseClass: "btn btn-default",
        		allowedFileExtensions : ['jpg', 'png'],
		        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
			});
			$(document).ready(function(){
				$("#imagen").submit( function(event){
					event.preventDefault();
					var formData = new FormData();
					formData.append('imagen',$("input[name='image']")[0].files[0])
					var token = $( "input[name='_token']" ).val();

					$.ajax({
						url: '/upload/image',
						headers: {
							'X-CSRF-TOKEN': token
						},
						type: 'POST',
						data: formData,
						async: false,
						processData: false,
						contentType: false,
						enctype: 'multipart/form-data',
						success: function(response){
							$("#imagen-input").val(response.path);
						}
					});
				});
			});
			$(document).ready(function(){
			    var clipboard = new Clipboard('#btn-copy');
			    clipboard.on('success', function(e) {
			        console.log(e);
			    });
			});
		    $(document).ajaxStop(function(){
				$("#path").show();
				$('#obtener-link').hide();
			});
			$(document).ajaxStop(function(){
				$("input[name='image']").change( function(){
						$('#path').hide();
						$('#obtener-link').show();
				});
			});
		</script>
@endsection