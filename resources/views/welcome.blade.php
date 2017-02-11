@extends('layouts.app')

@section('title', 'Welcome' )
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('cropper/dist/cropper.css') }}">
	<style type="text/css">
		body{
			background: url('{{ asset("storage/image/recursos-blogs.jpg") }}');
			background-repeat: no-repeat;
			background-position: center;
			background-attachment: fixed;
			font-family: 'Muli', sans-serif;
		}
	</style>

@endsection
@section('content')
    <div class="container">
        <div class="content">
            <div class="title">Welcome.</div>
        </div>
    </div>
@endsection
