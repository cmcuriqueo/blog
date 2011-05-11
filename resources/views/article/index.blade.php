@extends('layouts.app')

@section('title', 'Articulos' )

@section('content')
<div class="container">
	<div class="row">
	    <div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class="text-center">Todos mis Articulos
								<div class="pull-right">
									<a href="/articles/create" title="Crear Articulo">Crear Articulo</a>
								</div>
							</div>
						</h3>
					</div>

					<div class="panel-body">
						<div class="list-group">
							@forelse ($articles as $article)
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
							@empty
							<h3 class="text-center">No tiene articulos creados!.</h3>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</div>
		@if(Session::has('success'))
			@include('layouts.push')
		@endif
	</div>
</div>
@endsection