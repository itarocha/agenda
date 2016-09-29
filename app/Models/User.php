<?php

namespace App\Models;

use Model;

class User extends Model
{
    protected $table = 'users';

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

}
