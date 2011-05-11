					<table class="table table-hover" >
						@forelse ($tags as $tag)
							<tr>
								<td>
								<a href="/tags/{{ $tag->id }}" class="btn btn-link pull-left">{{ $tag->name }} </a>
								</td>
								@if((Auth::user()->id == $tag->user_id 
									|| Auth::user()->type != 'member') && $tag->id != 1)
								<td>
									<a href="/tags/{{ $tag->id }}/edit" class="btn btn-link pull-right"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a>
								</td>
								<td>
									<form id="delete" action="/tags/{{ $tag->id }}" method="POST">
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
								<td>No se han encontrado tags con el nombre: {{ $search }}.</td>
								@else
								<td>No hay tag registrados.</td>
								@endif
							</tr>
						@endforelse
					</table>