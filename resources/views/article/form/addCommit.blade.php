						@forelse($commits as $commit)
						<div class="col-md-10 col-md-offset-1">
							<div class="panel panel-info">
								<div class="panel-heading">
									{{ $commit->user->name }} <div class="pull-right">{{ $commit->created_at }}</div><br>
											Rol: {{ $commit->user->type != 'member' ? 'Administrador' : '' }}
												 {{ $commit->user->type == 'member' ? 'Usuario' : '' }}
								</div>

								<div class="panel-body">
									<p>
										{{ $commit->commit }}
										@if($commit->user_id == Auth::user()->id)
											<div class="pull-right">
												<a href="" title="Eliminar comentario">Eliminar</a>
											</div>
										@endif
									</p>
									<a id="responder" href="/articles/{{ $article->id }}/commit/{{ $commit->id }}/show" title="Responder">Responder</a>
								</div>
							</div>
							<hr>
						</div>
						@empty
							<h4 class="text-center">No hay comentarios.</h4>
						@endforelse
						<div class="col-md-10 col-md-offset-1">
							<form id="addCommitForm" action="/articles/{{ $article->id }}/commit" method="POST">

								{{ csrf_field() }}

								<div class="form-group">
									<textarea class="form-control" name="commit"></textarea>
								</div>

								<div class="form-group">
									<button class="btn btn-default" type="submit">
										Comentar
									</button>
								</div>

							</form>
						</div>
