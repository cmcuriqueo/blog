<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Article;
use App\Tag;

class TagController extends Controller
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

    	$tags = Tag::all();

    	return view('tag.index', compact('tags'));
    
    }

    public function show(Tag $tag){

   		$tag->load('user');

        $articles = Article::activos()
                        ->where('public', 1)
                            ->whereExists( function($query) use ($tag){
                                $query->select('article_id as id')->from('article_tag')->where('tag_id', $tag->id);
                            })->get();

  		return view('tag.show', compact('tag', 'articles'));
    }

    public function store(Request $request){

        $rules = array(
            'name' => 'required|min:3|unique:tags|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/'
        );

        $messages = array(
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener un minimo de 3 caracteres',
            'name.unique' => 'El nombre ya se encuentra registrado',
            'name.regex' => 'Para el nombre solo permiten caracteres alfanumericos'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return \Redirect::back()->withInput()->withErrors($validator);
        } else {
           	$tag = new Tag($request->all());
            $tag->addUser(Auth::user()->id);
            \Session::flash('title', $tag->name);
            \Session::flash('success', 'Se ha creado correctamente.');
     		return back();
        }

    }

	public function edit(Tag $tag)
	{
        if($tag->estado == 1){
            return view('tag.form.edit', compact('tag'));
        } else {
            return abort(404);
        }
	}

    public function update(Request $request, Tag $tag){

        $rules = array(
            'name' => 'required|min:3|unique:tags,name,id|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/'
        );

        $messages = array(
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener un minimo de 3 caracteres',
            'name.unique' => 'El nombre ya se encuentra registrado',
            'name.regex' => 'Para el nombre solo permiten caracteres alfanumericos'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return \Redirect::back()->withInput()->withErrors($validator);
        } else {

            $tag->update([
                'name' => $request->name
            ]);

            \Session::flash('title', $tag->name);
            \Session::flash('success', 'Se ha actualizado correctamente.');

            return redirect('tags/');
        }
    }

    public function destroy(Tag $tag){

        $articles = Article::whereExists( function($query) use ($tag){
                                $query->select('article_id as id')->from('article_tag')->where('tag_id', $tag->id);
                            })->get();

        \DB::table('article_tag')->where('tag_id', $tag->id)->delete();

        foreach ($articles as $article) {
            if (empty($article->tags())) {
                $this->tags()->attach(Tag::where('id', 1)->get());
            }
        }

    	if($tag->delete()){
			\Session::flash('title', $tag->name);
			\Session::flash('success', 'Se ha eliminado correctamente.');
    	} else {
			\Session::flash('title', $tag->name);
			\Session::flash('success', 'No se ha podido eliminado.');
    	}
   		return redirect('tags/');
    }

    public function search(Request $request){
        if($request->ajax()){
            if(isset($request->search)){
                $tags = Tag::where('name', 'LIKE' ,'%'.$request->search.'%')->get();

                return response()->view('tag.form.search', ['tags' => $tags, 'search' => $request->search]);
            } else {
                $tags = Tag::all();
                return response()->view('tag.form.search', ['tags' => $tags]);
            }
        } else {
            return abort(503);
        }
    }
}
