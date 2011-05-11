<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    protected $table = "puntuaciones";
    protected $fillable = ['article_id', 'user_id', 'punto'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function article()
    {
    	return $this->belongsTo('App\Article');
    }
}
