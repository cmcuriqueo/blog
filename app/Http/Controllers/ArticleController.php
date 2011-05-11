<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HistorialUsuario;
use App\Puntuacion;
use App\Category;
use App\Article;
use App\Commit;
use App\Tag;

class ArticleController extends Controller
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

        $user = Auth::user();
        if($user){
            $articles = $user->articles()->activos()
                            ->where('estado',1)->orderBy('created_at', 'desc')->get();

            $articles->load('category', 'tags');

        	return view('article.index', compact('articles'));
        } else {
            return redirect()->action(
                'WelcomeController@welcome'
            );
        }

    }

    public function show($nombre_articulo){
        
        $nombre = str_replace('-', ' ', $nombre_articulo);

        $article = Article::activos()->where( 'title', $nombre )->first();
        
        if($article){
            $historial = HistorialUsuario::where([
                    ['user_id', Auth::user()->id]
                ])->orderBy('created_at','asc')
                    ->first();
            if($historial) {
                $articlesRecomendados = Article::where([
                        ['estado', '=', 1], 
                        ['public', '=', 1],
                        ['user_id', '!=', Auth::user()->id],
                        ['id', '!=', $article->id]
                    ])->where('category_id', '=' ,$historial->category_id)
                        ->limit(3)
                            ->get();
            } else {
                $articlesRecomendados = Article::where([
                        ['estado', '=', 1], 
                        ['public', '=', 1],
                        ['user_id', '!=', Auth::user()->id],
                        ['id', '!=', $article->id]
                    ])->limit(3)
                        ->get();
            }

            $puntuacion = Puntuacion::where('article_id', $article->id)
                ->sum('punto');

            $article->load('user','category', 'tags');
            
            if(Auth::user()->id != $article->user_id){
                HistorialUsuario::create([ 
                    'user_id' => Auth::user()->id, 
                    'category_id' => $article->category->id
                ]);
            }
            $commits = Commit::where('article_id', $article->id)->orderBy('created_at', 'asc')->get();

            return view('article.show', 
                    [
                        'article' => $article, 'puntuacion' => $puntuacion, 'articlesRecomendados' => $articlesRecomendados, 
                        'commits' => $commits
                    ]);

        } else {
            
            abort(404);
        }
    }
    
    public function create(){


        $tags = Tag::activos()->get();
        if(empty($tags)){
            return redirect()->action(
                    'TagController@index'
                );
        }
        
        $categories = Category::all();
        if(empty($categories)){
            return redirect()->action(
                    'CategoryController@index'
                ); 
        }

        if(!empty($categories) && !empty($tags)){
            return view('article.form.create', compact('tags', 'categories'));
        }
    }

    public function store(ArticleRequest $request){     

        $newArticle = new Article($request->except('tags'));
        $newArticle->addUser(Auth::user()->id);
        $newArticle->tags()->attach($request->tags);
        \Session::flash('title', $newArticle->title);
        \Session::flash('success', 'Se ha creado correctamente.');

        return redirect('articles/');
    }

    public function edit(Article $article){
        $article->load('category','tags');
        $tags = Tag::activos()->get();
        $categories = Category::all();
        $selectTags = $article->tags;
        return view('article.form.edit',compact('article','tags','categories', 'selectTags'));
    }

    public function update(Request $request, Article $article){

        $rules = array(
            'title' => 'required|min:4|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            'description' => 'required|min:10',
            'content' => 'required|min:30',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|exists:tags,id',
            'public' => 'required|boolean'
        );

        $messages = array(
                'title.required' => 'El titulo es requerido',
                'title.min' => 'El titulo debe tener un minimo de 4 caracteres',
                'title.unique' => 'El titulo ya se encuentra registrado',
                'description.required' => 'La descripción es requerida',
                'description.min' => 'La descripción debe tener un minimo de 10 caracteres',
                'title.regex' => 'Para el titulo solo permiten caracteres alfanumericos',
                'category_id.required' => 'La categoria es requerida',
                'category_id.exists' => 'La categoria es requerida',
                'tags.required' => 'Los tags son requeridos',
                'tags.exists' => 'Los tags son requeridos'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return \Redirect::back()->withInput()->withErrors($validator);
        } else{
            $article->update([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'public' => $request->public
            ]);

            $article->updateTags($request->tags);

            \Session::flash('title', $article->title);
            \Session::flash('success', 'Se ha actualizado correctamente.');

            return redirect('articles/');
        }
    }

    public function destroy(Article $article){
        
        $article->Baja();

        return redirect()->action(
            'ArticleController@index'
        ); 
    }

    public function selectCategoryAjax(Request $request){
        if ($request->ajax()) {
            $categories = Category::where('name', $request->buscar_categoria)->pluck('name','id')->all();

            $data = view('category.categories_select', compact('categories'))->render();
            return response()->json(['options' => $data]);
        }
    }
}
