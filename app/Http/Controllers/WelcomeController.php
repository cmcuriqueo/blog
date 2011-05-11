<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Article;

class WelcomeController extends Controller
{
    public function welcome(){
        if (Auth::guest()){
            return view('welcome');
        } else {
            return redirect()->action(
                'HomeController@index'
            ); 
        }
    }
}
