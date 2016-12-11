<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function session()
    {
        return $this->hasMany('App\Session');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    public function chat()
    {
        return $this->hasMany('App\Chat');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

}
