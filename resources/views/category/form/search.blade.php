					<table class="table table-hover">
						@forelse ($categories as $category)
							<tr>
								<td>
									<a class="btn btn-link" href="/categories/{{ $category->id }}">{{ $category->name }}</a>
								</td>
								@if((Auth::user()->id == $category->user_id 
									|| Auth::user()->type != 'member') && $category->id != 1)
								<td>
									<a href="/categories/{{ $category->id }}/edit" class="btn btn-link pull-right"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
								</td>
								<td>
									<form id="delete" action="/categories/{{ $category->id }}" method="POST">
										<input name="_token" type="hidden" value="{{ csrf_token() }}" />
										{{ method_field('DELETE') }}
										<button class="btn btn-link pull-right" type="submit"><span class='glyphicon glyphicon-trash'></span> Eliminar</button>
									</form>
								</td>
								@else
									<th colspan="2"></th>
								@endif
							</tr>
						@empty
							<tr> 
								@if(isset($search))
								<td>No se han encontrado categorias con el nombre: {{ $search }}.</td>
								@else
								<td>No hay categorias registradas.</td>
								@endif
							</tr>
						@endforelse
					</table>