<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Article;
use App\Puntuacion;

class PuntuacionController extends Controller
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

    public function votar($article, $voto){
    	$article = Article::findOrFail($article);

    	$ya_voto = Puntuacion::where([
				['user_id', '=', Auth::user()->id],
				['article_id', '=', $article->id],
				['punto', '=', $voto]
    		])
    			->orWhere([
					['user_id', '=', Auth::user()->id],
					['article_id', '=', $article->id],
					['punto', '!=', $voto]
	    		])->first();

    	if($ya_voto){

       		$ya_voto->update(['punto' => $voto]);

    	} else {

	    	Puntuacion::create([
	    		'user_id' => Auth::user()->id,
				'article_id' => $article->id,
				'punto' => $voto
	    		]);

    	}
	    return redirect('articles/'.str_replace(' ', '-', $article->title));

    }
}
