							@forelse($commits as $commit)
								<div class="col-md-10 col-md-offset-1">
									<div class="panel panel-default">
										<div class="panel-heading">
											{{ $commit->user->name }} <div class="pull-right">{{ $commit->created_at }}</div><br>
											Rol: {{ $commit->user->type != 'member' ? 'Administrador' : '' }}
												 {{ $commit->user->type == 'member' ? 'Usuario' : '' }}
										</div>

										<div class="panel-body">
											<p>{{ $commit->commit }}
											@if($commit->user_id == Auth::user()->id)
												<div class="pull-right">
													<form method="POST" action="{{ url('/articles/'.$article->id.'/commit/'.$commit->id) }}">
														{{ method_field('DELETE') }}
														<input name="_token" type="hidden" value="{{ csrf_token() }}" />
														<button class="btn btn-link" type="submit">Eliminar</button>
													</form>
												</div>
											@endif
											</p>
										</div>
									</div>
								</div>
								<hr>

							@empty

								<h4 class="text-center">No hay comentarios.</h4>

							@endforelse
