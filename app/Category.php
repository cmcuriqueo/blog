<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";

    protected $fillable = ['name'];

    public function articles()
    {
    	return $this->hasMany('App\Article');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function historial()
    {
    	return $this->hasMany('App\HistorialUsuario');
    }

    public function addUser($userId)
    {
        $this->user_id = $userId;
        
        return $this->save();
    }

    public function scopeActivos($query){
        return $query->where( 'estado', true );
    }
}
