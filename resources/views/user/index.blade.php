@extends('layouts.app')

@section('title', 'Usuarios' )

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="table-responsive">  
                <table class="table">
    				<tr>
    					<td>Nombre</td>
    					<td>Email</td>
    					<td>Rol</td>
    				</tr>
    			    @forelse($users as $user)
    				<tr>
    				    <td><a href="{{ url('/user/'.$user->name) }}">{{ $user->name }}</a></td>

    					<td>{{ $user->email }}</td>

                        <td>{{{ $user->type }}}</td>
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

