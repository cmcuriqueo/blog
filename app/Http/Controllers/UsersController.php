<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function index(){
     	if(Auth::user()->type != 'member'){

	     	$users = User::where('estado', 1)
	     				->where('type','!=','super_admin')
	     				->where('id','!=', Auth::user()->id)->get();

	     	return view('user.index',compact('users'));

	     } else {

	     	return abort(404);

	     }
     }

    public function editTipo(Request $request, User $user){

    	$user->type = $request->form[1]['value'];
    	$user->save();

    	return response()->json(['status' => 'Se ha cambiado correctamente']);
    }

}
