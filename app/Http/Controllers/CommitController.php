<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HistorialUsuario;
use App\Puntuacion;
use App\Category;
use App\Article;
use App\Commit;
use App\Tag;

class CommitController extends Controller
{


    public function store(Request $request, Article $article){

    	if($request->ajax()){

    	   	$newCommit = Commit::create([
    	   		'commit' => $request->commit,
    	   		'user_id' => Auth::user()->id,
    	   		'article_id' => $article->id
    	   	]);

            $article->load('user','category', 'tags');
            

            $commits = Commit::where('article_id', $article->id)->orderBy('created_at', 'asc')->get();
            
            return response()->view('article.form.addCommit', [
                        'article' => $article,  
                        'commits' => $commits
                    ]);
    	} else {

    		return abort(503);
    	
        }

    }

    public function destroy(Article $article, Commit $commit){
        if ($commit->user_id == Auth::user()->id) {
            $commit->delete();
            $nombre = str_replace(' ', '-', $article->title);
            return redirect()->action(
                'ArticleController@show', $nombre 
            );
        } else {
            return abort(503);
        }
    }

}
