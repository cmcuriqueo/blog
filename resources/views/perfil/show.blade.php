@extends('layouts.app')

@section('title', $user->name )
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('cropper/dist/cropper.css') }}">
	<style type="text/css">
		.btn{
			background-color: transparent;
			border-color: transparent;
		}
		.thumbnail {
			margin-bottom: 0%;
		}
		.btn-info {
			background-color: #5bc0de;
		}
	</style>

@endsection
@section('content')
	<div class="container">
		<div class="text-center" id="result"></div>
		<div class="col-md-12">
			<div class="panel">	
				<div class="panel-body">	
					<div class="col-md-2">
						<div class="row">
							<a href="{{ asset('storage/'.$perfil->imagen) }}" class="thumbnail">
								<img class="img-responsive" alt="Responsive image" src="{{ asset('storage/'.$perfil->imagen) }}" alt="{{ $perfil->imagen }}">
							</a>
							@if($user->id === Auth::user()->id)

						    	<!-- Button trigger modal -->
				    		<div class="text-center">
						    	<button type="button" class="btn" data-target="#modal" data-toggle="modal">
							      	Cambiar Foto
						    	</button>
						    </div>

						    <!-- Modal -->
						    <div class="modal fade" id="modal" aria-labelledby="modalLabel" role="dialog" tabindex="-1">
						    	<div class="modal-dialog" role="document">
						       		<div class="modal-content">
						       			<div class="modal-header">
						           			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						           				<span aria-hidden="true">&times;</span>
						           			</button>
						           			<h4 class="modal-title" id="modalLabel">Cambiar foto de perfil</h4>
						       			</div>
						          		<div class="modal-body">
							              	<div id="image-container"></div>
								    		<form id="form-image" method="POST" action="/user/{{ Auth::user()->name }}/foto" enctype="multipart/form-data">
								    			{{ csrf_field() }}
								        		<input type="hidden" name="y" id="y" />
								        		<input type="hidden" name="x" id="x" />
								        		<input type="hidden" name="w" id="w" />
								        		<input type="hidden" name="h" id="h" />
								        		<input required class="btn btn-default" type="file" name="imagen" id="image-file" />
										        <div class="pull-right">
										        	<button type="submit" class="btn btn-default">Cambiar</button>
										        </div>
								    		</form>
								    		<br>
					            		</div>

						          	</div>
						        </div>
						   </div>
						</div>

						@endif

					</div>
					<div class="col-md-4 col-md-offset-5">
						<div class="panel">	
							<div class="panel-body">
								<p>Total Articulos : {{ $totalArticulos }}</p>
								<p>Puntuacion Total: {{ $puntuacion }}</p>
								@if( $user->id == Auth::user()->id ||
									Auth::user()->type == 'member' || 
									( Auth::user()->type == 'admin' && Auth::user()->type == 'admin') )
								<p>Rol: 
									{{ $user->type == 'admin' ? 'Administrador' : '' }}
									{{ $user->type == 'super_admin' ? 'Super Administrador' : '' }}
									{{ $user->type == 'member' ? 'Usuario' : '' }}
								</p>
								@else
			                    <form>
				    				<input name="_token" type="hidden" value="{{ csrf_token() }}" />
				    				<label for="type">Rol: </label>
				    				<select id="type" name="type">
				    					<option value="member" {{ $user->type == 'member' ? ' selected' : '' }}>member</option>
				    					<option value="admin" {{ $user->type == 'admin' ? ' selected' : '' }}>admin</option>
				    				</select>
			                    </form>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="container">
		<div class="col-md-12">
			<ul id="myTabs" class="nav nav-tabs" role="tablist">
				<li role="presentaion" class="active">
					<a href="#articles" title="Articulos" aria-controls="articles" role="tab" data-toggle="tab">Articulos</a>
				</li>
				<li role="presentaion">
					<a href="#informacion" title="Informacion" aria-controls="informacion" role="tab" data-toggle="tab">Informacion</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">

			<div class="tab-pane active" role="tabpanel" id="articles" >
				<div class="col-md-12 ">
					<br><br>
					<div class="list-group">
					@forelse ($articles as $article)
						<a href="/articles/{{ str_replace(' ', '-', $article->title) }}/" class="list-group-item">
							<h4 class="list-group-item-heading">
								{{ $article->title }}
							</h4>
							<div class="list-group-item-text">
								{{ $article->description }}
								<div class="text-right">
									<span class="badge">{{ $article->category->name }}</span>
								</div>
							</div>
						</a>
					@empty
						<h3 class="text-center">No tiene articulos creados!.</h3>
					@endforelse
					</div>
				</div>
			</div>
			<div class="tab-pane" role="tabpanel" id="informacion">
				<div class="col-md-12">
					<br><br>
					<div class="panel">
						<div class="panel-body">
							<p>Descripcion: {{ $perfil->descripcion }}</p>
							<p>Informacion: {{ $perfil->informacion }}</p>
							<div class="pull-right">
								<a href="/user/{{ $user->name }}/edit" class="btn btn-info">
									Editar
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@if(Session::has('success'))
		@include('layouts.push')
	@endif
@endsection
@section('script')
	@if(Auth::user()->id == $user->id)
   	<script src="{{ asset('cropper/dist/cropper.js') }}"></script>
	<script>
	    $(function () {
	      var $image = $('#image');
	      var cropBoxData;
	      var canvasData;

	      $('#modal').on('shown.bs.modal', function () {
	       	var $imageContainer = $('#image-container');

		    $('#image-file').change(function () {

		        var src = window.URL.createObjectURL(this.files[0]);

		        var $image = $('<img/>');

		        $image.attr({src: src}).on('load',function () {

		            $imageContainer.html($image);       

		            $image.cropper({
		            viewMode: 1,
		            aspectRatio: 200 / 200,
					resizable: false,
		          	zoomable: false,
		          	rotatable: false,
		          	multiple: true,
		            crop: function (e) {

		                $('#x').val(e.x);
		                $('#y').val(e.y);
		                $('#w').val(e.width);
		                $('#h').val(e.height);
		              }
		            });
		        })
		    });
	      }).on('hidden.bs.modal', function () {
	        $image.cropper('destroy');
	        $('#image-container').empty();
	        $('#form-image').trigger("reset");
	      });
	    });

	    $('#myTabs li a').click(function(event){
	    	event.preventDefault()
	    	$( this ).tab('show');
	    });

	</script>
	@endif

		@if( $user->id == Auth::user()->id ||
				Auth::user()->type == 'member' || 
				( Auth::user()->type == 'admin' && 
				Auth::user()->type == 'admin') 
			)
		<script type="text/javascript">
		$(document).ready(function() {
			$('#type').on('change', function(event){ 
                event.preventDefault();
                var type = $(this).val();
                var token = $("input[name='_token']").val();
  
				var url = '{{ url('/user/'.$user->id.'/type/change/') }}';

				$.post( url, {_token: token, type: type}, function( data ){
					$('#result').html( '<br><span class="alert alert-success alert-dismissible text-center">'+data.status+'</span><br><br>' ).show(2000);
                    setTimeout(function() {
                        $("#result").fadeOut(1500); 
                    },3000);
				});
			});
		});
		</script>
		@endif

@endsection