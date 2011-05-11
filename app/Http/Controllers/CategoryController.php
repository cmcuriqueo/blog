<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HistorialUsuario;
use App\Category;
use App\Article;

class CategoryController extends Controller
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
    	$categories = Category::all();

    	return view('category.index', compact('categories'));
    }

    public function show(Category $category){

    	$category->load('user');
        
        $articles = Article::where([
                    ['estado', '=', 1], 
                    ['public', '=', 1],
                    ['category_id', '=', $category->id]
            ])->inRandomOrder()->get();
        
        HistorialUsuario::create([ 
            'user_id' => Auth::user()->id, 
            'category_id' => $category->id
        ]);

    	return view('category.show', compact('category', 'articles'));
    
    }

    public function store(Request $request){

        $rules = array(
            'name' => 'required|min:3|unique:categories|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/'
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
            $category = new Category($request->all());
            $category->addUser(Auth::user()->id);
            \Session::flash('title', $category->name);
            \Session::flash('success', 'Se ha creado correctamente.');
            return back();
        }

    }

	public function edit(Category $category)
	{
		return view('category.form.edit', compact('category'));
	}

    public function update(Request $request, Category $category){

        $rules = array(
            'name' => 'required|min:3|unique:categories,name|regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s]+$/'
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
            $category->update([
                'name' => $request->name
            ]);
            \Session::flash('title', $category->name);
            \Session::flash('success', 'Se ha actualizado correctamente.');

            return redirect('categories/');
        }
    }

    public function destroy(Category $category){
        HistorialUsuario::where('category_id', $category->id)->delete();

        Article::where('category_id', $category->id)->update(['category_id' => 1]);
        
        if($category->delete()){
            \Session::flash('title', $category->name);
            \Session::flash('success', 'Se ha eliminado correctamente.');
        } else {
            \Session::flash('title', $category->name);
            \Session::flash('success', 'No se ha podido eliminado.');
        }
        return redirect('categories/');
    }

    public function search(Request $request){
        if($request->ajax()){
            if(isset($request->search)){
                $categories = Category::where('name', 'LIKE' ,'%'.$request->search.'%')->get();

                return response()->view('category.form.search', ['categories' => $categories, 'search' => $request->search]);
            } else {
                $categories = Category::all();
                return response()->view('category.form.search', ['categories' => $categories]);
            }
        } else {
            return abort(503);
        }
    }

}
