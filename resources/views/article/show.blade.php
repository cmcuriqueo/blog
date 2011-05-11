@extends('layouts.app')

@section('title', $article->title )

@section('content')
		@if( Auth::user()->id == $article->user_id)
		<div class="container">
			<div class="row">
				<div class="pull-right">
					<a href="/articles/{{ $article->id }}/edit" title="editar" class="btn btn-link pull-right"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
					<form action="/articles/{{ $article->id }}" method="POST">
						<input name="_token" type="hidden" value="{{ csrf_token() }}" />
						{{ method_field('DELETE') }}
						<button class="btn btn-link " type="submit">
							<span class='glyphicon glyphicon-trash'></span> Eliminar
						</button>
					</form>
				</div>
			</div>
		</div>
		@endif
		<div class="container">
			<div class="row">
				<div class="alert alert-warning alert-dismissible text-center">{{ $article->description }}</div>
				<hr>
				<?php echo $article->content; ?>
				
			</div>
		</div>
		<br/><br/>
		<div class="container">
			<div class="row">
				<div class="panel panel-info">
					<div class="panel-body">
						<label>Categoria: </label>
						<a href="/categories/{{ $article->category->id }}/">
							<span class="badge"> {{  $article->category->name }} </span>
						</a><br>
						<label>Tags: </label>
						@foreach ($article->tags as $tag)
						<a href="/tags/{{ $tag->id }}/">
							<span class="badge"> {{ $tag->name }} </span>
						</a>
						@endforeach
						<br>
						<div class="pull-left">
						Puntuacion: {{ $puntuacion }}<br>
						@if(Auth::user()->id != $article->user_id)
						Puntuar: 
						<a href="/puntuar/{{ $article->id }}/1/"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></a>
						 
						<a href="/puntuar/{{ $article->id }}/-1/"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></a>
						</div>
						<br>
						@endif
						
						<hr>
						<address>
						@if($tag->user->estado == 1)
							<div class="text-uppercase"><a href="/user/{{ $article->user->name }}">{{ $article->user->name }}</a>
							</div><br>
						@else
							<div class="text-uppercase">{{ $article->user->name }}</div><br>
						@endif
						</address>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-info">
						<div class="panel-body">

						@forelse ($articlesRecomendados as $article)

							<div class="col-md-4">

								<a href="/articles/{{ str_replace(' ', '-', $article->title) }}/" class="list-group-item">
									<h4 class="list-group-item-heading">
										{{ $article->title }}
									</h4>
									<p class="list-group-item-text">
										{{ $article->description }}
										<div class="text-right">
											<span class="badge">{{ $article->category->name }}</span>
										</div>
									</p>
								</a>

							</div>

						@empty

							<h3 class="text-center">No hay articulos recomendados!.</h3>

						@endforelse

						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="text-center" >Comentarios: </div>
						</div>	
						<div class="panel-body" id="addcommit">
							@forelse($commits as $commit)
								<div class="col-md-10 col-md-offset-1">
									<div class="panel panel-default">
										<div class="panel-heading">
											{{ $commit->user->name }} <div class="pull-right">{{ $commit->created_at }}</div><br>
											Rol: {{ $commit->user->type != 'member' ? 'Administrador' : '' }}
												 {{ $commit->user->type == 'member' ? 'Usuario' : '' }}
										</div>

										<div class="panel-body">
											<p>{{ $commit->commit }}
											@if($commit->user_id == Auth::user()->id)
												<div class="pull-right">
													<a href="" title="Eliminar comentario">Eliminar</a>
												</div>
											@endif
											</p>
										</div>
									</div>
								</div>
								<hr>

							@empty

								<h4 class="text-center">No hay comentarios.</h4>

							@endforelse

							<div class="col-md-10 col-md-offset-1">
								<form id="addCommitForm" action="/articles/{{ $article->id }}/commit" method="POST">

									{{ csrf_field() }}

									<div class="form-group">
										<textarea class="form-control" name="commit"></textarea>
									</div>

									<div class="form-group">
										<button class="btn btn-default" type="submit">
											Comentar
										</button>
									</div>

								</form>
							</div>

						</div>					
					</div>
				</div>
			</div>
		</div>
@endsection

@section('script')

	<script  type="text/javascript" >
		$(document).ready(function() {
			$("#addCommitForm").submit(function(event){
				event.preventDefault();
				var form = $( this );
				var commit = $( "textarea[name='commit']" ).val();
				var token = $( "input[name='_token']" ).val();
				var url = form.attr( 'action' );
				$('#addcommit').load(url, { _token : token, commit : commit },function(responseTxt, statusTxt, xhr){});
			});
		});
	</script>

@endsection