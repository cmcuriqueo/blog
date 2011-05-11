@extends('layouts.app')

@section('title', $tag->name )

@section('content')
<div class="container">
	<div class="col-md-4">
		<ol class="breadcrumb">
			<li><a href="/home/" title="Home">Home</a></li>
			<li><a href="/tags/" title="Tags">Tags</a></li>
			<li class="active">{{ $tag->name }}</li>
		</ol>
	</div>
	<div class="col-md-12">		
		<h3 class="text-center">{{ $tag->name }}</h3><br>
		@if(Auth::user()->type != 'member')
			@if($tag->user->estado == 1)
			<div class="pull-right">Creador: 	<a href="/user/{{ $tag->user->name }}">
													{{ $tag->user->name }}
												</a>
			</div> <br>
			@else
			<div class="pull-right">Creador: {{ $tag->user->name }}</div> <br>
			@endif
		<div class="pull-right">Fecha y Hora de Creación: {{ $tag->created_at }}</div> <br>
		<div class="pull-right">Fecha y Hora de Ultima Modificacion: {{ $tag->updated_at }}</div> <br>
		@endif
		<h2 class="text-center">Articulos Asociados</h2>
		<table class="table table-codensed">
			
			@forelse ($articles as $article)
			<tr>
				<td>
					<a href="/articles/{{ str_replace(' ', '-', $article->title) }}/">{{ $article->title }}</a>
				</td>
			</tr>
			@empty
				<tr>
				<td><div class="text-center">No hay articulos asociados a este tag.</div></td>	
				</tr>
			@endforelse
			
		</table>
	</div>

</div>
@endsection