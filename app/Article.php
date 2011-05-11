<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = "articles";

    protected $fillable = ['title', 'content', 'category_id', 'user_id', 'public', 'description'];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function images()
    {
    	return $this->belongsToMany('App\Image');
    }

    public function tags()
    {
    	return $this->belongsToMany('App\Tag');
    }

    public function puntuaciones()
    {
        return $this->belongsToMany('App\Puntuacion');
    }

    public function addUser($userId)
    {
        $this->user_id = $userId;
        
        return $this->save();
    }

    public function updateTags($tags){
        $this->tags()->detach();
        return $this->tags()->attach($tags);
    }
    
    public function scopePublic($query){
        return $query->where( 'estado', true );
    }

    public function scopeActivos($query){
        return $query->where( 'estado', true );
    }

    public function scopeBaja(){
        $this->estado = 0;
        
        return $this->save();
    }

}
