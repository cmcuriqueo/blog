<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    protected $table = "commits";

    protected $fillable = [ 'article_id', 'commit', 'user_id', 'category_id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function article()
    {
    	return $this->belongsTo('App\Article');
    }   
}
