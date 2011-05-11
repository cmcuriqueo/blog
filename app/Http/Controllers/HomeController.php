<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\HistorialUsuario;
use App\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = Auth::user();

        if ($user){

            $historial = HistorialUsuario::where([
                    ['user_id', Auth::user()->id]
                ])->orderBy('created_at','asc')
                    ->first();
            if($historial) {
                $articlesRecomendados = Article::where([
                        ['estado', '=', 1], 
                        ['public', '=', 1],
                        ['user_id', '!=', Auth::user()->id]
                    ])->where('category_id', '=' ,$historial->category_id)
                        ->limit(5)
                            ->get();
            } else {
                $articlesRecomendados = Article::where([
                        ['estado', '=', 1], 
                        ['public', '=', 1],
                        ['user_id', '!=', Auth::user()->id]
                    ])->limit(5)
                        ->get();
            }

            //ultimos articulos 
            $articlesUltimos = Article::where([
                    ['estado', '=', 1], 
                    ['public', '=', 1],
                ])->orderBy('created_at', 'desc')
                    ->limit(5)
                        ->get();

            //todos
            $articles = Article::where([
                    ['estado', '=', 1], 
                    ['public', '=', 1],
            ])->inRandomOrder()->paginate(  15  );

            $articles->load('user', 'category');

            return view('home', 
                    compact('articlesRecomendados', 'articlesUltimos', 'articles')
                );
        } else {
            return redirect()->action(
                'WelcomeController@welcome'
            ); 
        }
    }
}
