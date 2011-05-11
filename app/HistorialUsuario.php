<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialUsuario extends Model
{
    protected $table = "historial_usuario";

    protected $fillable = [ 'user_id', 'category_id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }

    public function scopeGetCategories(){
        $array = [];
        foreach ($this as $value) {
            $array += $value->category_id;    
        }
        return $array;
    }
}
