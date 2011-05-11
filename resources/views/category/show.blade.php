@extends('layouts.app')

@section('title', $category->name )

@section('content')
<div class="container">

	<div class="col-md-6">
		<ol class="breadcrumb">
			<li><a href="/home/" title="Home">Home</a></li>
			<li><a href="/categories/" title="Categories">Categories</a></li>
			<li class="active">{{ $category->name }}</li>
		</ol>
	</div>
	<div class="col-md-12">		
		<h3 class="text-center">{{ $category->name }}</h3>	<br>
		@if(Auth::user()->type != 'member')
		<div class="pull-right">Creador: {{ $category->user->name }}</div> <br>
		<div class="pull-right">Fecha y Hora de Creación: {{ $category->created_at }}</div> <br>
		<div class="pull-right">Fecha y Hora de Ultima Modificacion: {{ $category->updated_at }}</div> <br>
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
				<td><div class="text-center">No hay articulos asociados a esta categoria.</div></td>	
				</tr>
			@endforelse
			
		</table>
	</div>

</div>
@endsection