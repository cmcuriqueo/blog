<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tags";

    protected $fillable = ['name'];

    public function articles()
    {
    	return $this->belongsToMany('App\Article');
    }

    public function addUser($userId)
    {
        $this->user_id = $userId;
        
        return $this->save();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    
}
