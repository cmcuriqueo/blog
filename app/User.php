<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function perfil()
    {
        return $this->belongsTo('App\Perfil');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function historial()
    {
        return $this->hasMany('App\HistorialUsuario');
    }

    public function puntuaciones()
    {
        return $this->belongsToMany('App\Puntuacion');
    }

    public function commits()
    {
        return $this->belongsToMany('App\Commit');
    }

}
