<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $articlesRecomendados = \DB::select('SELECT articles.*, categories.name as category_name
                                                FROM articles 
                                                JOIN categories 
                                                    ON categories.id = articles.category_id
                                                WHERE articles.user_id != ?
                                                    AND articles.estado = ? 
                                                    AND articles.public = ? 
                                                    AND articles.id != ?
                                                    AND articles.category_id IN
                                                    (
                                                        SELECT historial_usuario.category_id as id 
                                                        FROM historial_usuario JOIN categories 
                                                            ON categories.id = historial_usuario.category_id 
                                                        WHERE historial_usuario.user_id = ? 
                                                        GROUP BY historial_usuario.category_id 
                                                        ORDER BY COUNT(historial_usuario.category_id) DESC 
                                                    )
                                                ORDER BY articles.title DESC 
                                                LIMIT 3', 
                                                    [
                                                        Auth::user()->id, 1, 1, $article->id, Auth::user()->id
                                                    ]
                                                );
            
            if(empty($articlesRecomendados)) {

                Log::info('No se encontraron articulos recomendados en la prueba!...');

                $articlesRecomendados = Article::where([
                        ['estado', '=', 1], 
                        ['public', '=', 1],
                        ['user_id', '!=', Auth::user()->id],
                        ['id', '!=', $article->id]
                    ])->limit(3)
                        ->get();
                $articlesRecomendados->load('category');
            }

            $puntuacion = Puntuacion::where('article_id', $article->id)
                ->sum('punto');

            $article->load('user','category', 'tags', 'commits');
            
            if(Auth::user()->id != $article->user_id){
                HistorialUsuario::create([ 
                    'user_id' => Auth::user()->id, 
                    'category_id' => $article->category->id
                ]);
            }

            return view('article.show', 
                    [
                        'article' => $article, 'puntuacion' => $puntuacion, 'articlesRecomendados' => $articlesRecomendados, 
                        'commits' => $article->commits
                    ]);

        } else {
            
            abort(404);
        }
    }
    
    public function create(){


        $tags = Tag::all();
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
        if(!strpos('<img class="img-responsive" ', $newArticle->content)){
            $newArticle->content = str_replace(
                array('../','<img '), 
                array('../../', '<img class="img-responsive" ') ,
                $newArticle->content );
        }

        $newArticle->addUser(Auth::user()->id);
        $newArticle->tags()->attach($request->tags);
        \Session::flash('title', $newArticle->title);
        \Session::flash('success', 'Se ha creado correctamente.');

        return redirect('articles/');
    }

    public function edit(Article $article){
        $article->load('category','tags');
        $tags = Tag::all();
        $categories = Category::all();
        $selectTags = $article->tags;
        return view('article.form.edit',compact('article','tags','categories', 'selectTags'));
    }

    public function update(Request $request, Article $article){
        $content = str_replace(
            array('<img ', '<img class="img-responsive" class="img-responsive"'), 
            array('<img class="img-responsive" ','<img class="img-responsive" ') ,
            $request->content );
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
                'title.regex' => 'Para el titulo solo se permiten caracteres alfanumericos',
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
                'content' => $content,
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

    public function uploadImagen(Request $request){
        if ($request->ajax()) {
            $rules = array(
                'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            );

            $messages = array(
                    'imagen.required' => 'La imagen es requerida',
                    'imagen.min' => 'El titulo debe tener un minimo de 4 caracteres'
            );

            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                 return response()->json(['error'=> $validator->errors()->all()]);
            } else {
                $file = $request->file('imagen');
                $path = 'image/'.Auth::user()->id.'/articles';
                $path = Storage::disk('public')->put($path , $file);

                return response()->json(['path'=> asset('storage/'.$path)]);
            }
        }
    }
}
