<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{

    protected $table = "perfiles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'informacion', 'descripcion', 'user_id', 'imagen'
    ];


    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
