<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likes()
    {
        return $this->hasMany('App\like');
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
