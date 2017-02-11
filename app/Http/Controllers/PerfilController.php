<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Puntuacion;
use App\Article;
use App\Perfil;
use App\User;

class PerfilController extends Controller
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

    
    public function show( $name ){
    	$user =            User::where('name', $name)->firstOrFail();

    	$perfil =          Perfil::where('user_id', $user->id)->firstOrFail();

        $puntuacion =       Puntuacion::whereExists( function($query) use ($user){
                                $query
                                    ->select('article_id as id')
                                    ->from('articles')
                                    ->where('user_id', $user->id);
                            })->sum('punto');
        $totalArticulos =   Article::where([
                                ['estado', 1],
                                ['user_id', $user->id]
                            ])->count();
        $articles =         Article::where([
                                ['estado', 1],
                                ['public', 1],
                                ['user_id', $user->id]
                            ])->get();

    	return view('perfil.show', 
                    compact('perfil', 'user', 'puntuacion', 'totalArticulos', 'articles'));
    }   

    public function edit( $name){
        $user =  User::where('name', $name)->firstOrFail();

    	if($user->id == Auth::user()->id){

            $perfil = Perfil::where('user_id', $user->id)->firstOrFail();
    		return view('perfil.form.edit', compact('user', 'perfil'));
    	} else {
    		return abort(403);
    	}
    }

    public function update(Request $request, $name){
        $user =  User::where('name', $name)->firstOrFail();

        if($user->id == Auth::user()->id){

            $perfil = Perfil::where('user_id', $user->id)->firstOrFail();
            
            $rules = array(
                'descripcion' => 'required|min:10',
                'informacion' => 'required|min:10'

            );

            $messages = array(
                'descripcion.required' => 'La descripcion es requerido',
                'descripcion.min' => 'La descripcion debe tener un minimo de 3 caracteres',
                'informacion.required' => 'La informacion es requerido',
                'informacion.min' => 'La informacion debe tener un minimo de 3 caracteres',
            );

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                return \Redirect::back()->withInput()->withErrors($validator);
            } else {

                $perfil->update($request->all());

                \Session::flash('title', 'Actulizacion');
                \Session::flash('success', 'Se ha actualizado correctamente su informacion.');

                return redirect()->action(
                        'PerfilController@show', $user->name
                    );
            }

        } else {
            return abort(403);
        }
    }

    public function editFoto( $name ){
        $user = User::where('name', $name)->firstOrFail();
        if($user->id == Auth::user()->id){
            return view('perfil.form.edit_foto');
        } else {
            return abort(403);
        }
    }

    public function updateFoto(Request $request, $name){

        $user = User::where('name', $name)->first();
        if($user){
            $perfil = Perfil::where('user_id', $user->id)->firstOrFail();
            
            $this->validate($request, [
                'imagen' => 'required',
            ]);
            $file = $request->file('imagen');
            //$file = Image::make($request->file('imagen'));
         
            //$file->crop( (integer)$request->w, (integer)$request->h, (integer)$request->x, (integer)$request->y );

            $path = 'image/'.$user->id.'/perfil/';

            $path = Storage::disk('public')->put($path , $file);

            $perfil->imagen = $path;
            $perfil->save();
            \Session::flash('title', 'Foto de perfil');
            \Session::flash('success','Se ha cambiado correctamente.');

            return redirect('/user/'.$user->name);
        } else {
            abort(404);
        }
    }
}

