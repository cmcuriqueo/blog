@extends('layouts.app')

@section('title', 'Welcome' )
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('cropper/dist/cropper.css') }}">

@endsection
@section('content')
    <div class="container-floid">
        <div class="content">
	        <div class="col-md-12">
	        	<img class="img-responsive" src="{{ asset("storage/image/recursos-blogs.jpg") }}">
	        </div>
        </div>
    </div>
@endsection
