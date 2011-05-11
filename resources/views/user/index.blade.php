@extends('layouts.app')

@section('title', 'Usuarios' )

@section('content')
<div class="container">
    <div class="row">
        <div class="text-center" id="result"></div>
        <div class="col-md-8 col-md-offset-2">
            <div class="table-responsive">  
                <table class="table">
    				<tr>
    					<td>Nombre</td>
    					<td>Email</td>
    					<td>Rol</td>
                        <td></td>
    				</tr>
    			    @forelse($users as $user)
    				<tr>
    				    <td><a href="/user/{{ $user->name }}">{{ $user->name }}</a></td>
    					<td>{{ $user->email }}</td>
                        <form action="/user/{{ $user->id }}/type/change/" id="formRol">
    					   <td>
	    					
	    						<input name="_token" type="hidden" value="{{ csrf_token() }}" />
	    						<div class="form-group">
	    						<select id="type" name="type">
	    							<option value="member" {{ $user->type == 'member' ? ' selected' : '' }}>member</option>
	    							<option value="admin" {{ $user->type == 'admin' ? ' selected' : '' }}>admin</option>
	    						</select>
	    						</div>
	    					
    					   </td>
                           <td><button class="btn btn-info" type="submit">Cambiar</button></td>
                        </form>
    				</tr>
                    @empty
                    <tr><td colspan="3"><h4 class="text-center">No hay usuarios registrados</h4></td></tr>
    			    @endforelse
    		  </table>
            </div>
    	</div>
    </div>
</div>
@endsection

@section('script')
	<script  type="text/javascript">
		$(document).ready(function() {
			$('form').submit( function(e){ 
                e.preventDefault();
                var form = $(this);
                var token = $("input[name='_token']").val();
  
				var url = form.attr( 'action' );

				$.post( url, {_token: token, form: form.serializeArray()}, function( data ){
					$('#result').html( '<br><span class="alert alert-success alert-dismissible text-center">'+data.status+'</span><br><br>' ).show(2000);
                    setTimeout(function() {
                        $("#result").fadeOut(1500); 
                    },3000);
				});
			});
		});
	</script>
@endsection