<!DOCTYPE html>
<html>
<head>
	<title>{{ $article->title }}</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body>
	<div class="container">
		Hola
		<br/><br/>
		<h1>{{ $article->title }}</h1>
		<hr/>
		{{ $article->content }}
		<hr/>

		{{ $article->user->name }} | {{ $article->category->name }} |

		@foreach($article->tags as $tag)
			{{ $tag->name }}
		@endforeach
	</div>
</body>
</html>


