<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', 'WelcomeController@welcome');


Route::group(['middleware' => ['web']], function (){
	/* Rutas para tags*/
	Route::post('/tags/search/','Tagcontroller@search');
	Route::post('/tags/','Tagcontroller@store');
	Route::get('/tags/{tag}/edit', 'TagController@edit');
	Route::patch('/tags/{tag}', 'TagController@update');
	Route::delete('/tags/{tag}', 'TagController@destroy');

	/* Rutas para Categorias*/
	Route::post('/categories/search/','CategoryController@search');
	Route::post('/categories/','CategoryController@store');
	Route::get('/categories/{category}/edit', 'CategoryController@edit');
	Route::patch('/categories/{category}', 'CategoryController@update');
	Route::delete('/categories/{category}', 'CategoryController@destroy');

	/* Rutas para Articulos*/
	Route::resource('/articles','ArticleController', 
		[ 'except' => ['show']
	]);
	Route::get('/puntuar/{articulo}/{voto}/', 'PuntuacionController@votar');

	Auth::routes();
	Route::get('/home', 'HomeController@index');

	Route::get('/user/{name}/', 'PerfilController@show');

	Route::get('/user/{name}/edit', 'PerfilController@edit');
	Route::patch('/user/{name}/', 'PerfilController@update');
	
	Route::post('/user/{name}/foto', 'PerfilController@updateFoto');
	Route::get('/user/{name}/foto/edit', 'PerfilController@editFoto');

	Route::get('/tags/{tag}', 'TagController@show');
	Route::get('/tags/', 'TagController@index');

	Route::get('/categories/{category}', 'CategoryController@show');
	Route::get('/categories/', 'CategoryController@index');


	Route::get('/articles/{nombre_articulo}/', 'ArticleController@show');

	Route::get('/users/', 'UsersController@index');

	Route::post('/user/{user}/type/change', 'UsersController@editTipo');

	Route::post('/articles/{article}/commit/', 'CommitController@store');

	Route::delete('/articles/{article}/commit/{commit}','CommitController@destroy');



});
Route::post('/upload/image/', 'ArticleController@uploadImagen');
