<option value="0">Selecciones una Categoria</option>}
@if (!empty($categories))
	@foreach ($array as $value)
		<option value="{{ $value->id }}">{{ $value->name }}</option>
	@endforeach
@endif