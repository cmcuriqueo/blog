<!DOCTYPE html>
<html>
<head>
	<title>@yield('title', 'Default') | Panel de Administrción</title>
	<script src="{{ asset('js/jquery/dist/jquery.min.js') }}"></script>
	<script src="{{ asset('css/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body>

	@include('admin.template.partials.nav');
	<section>
		@yield('content')
	</section>

</body>
</html>