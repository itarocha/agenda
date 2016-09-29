<?php

namespace App\Models;

use Model;

class Category extends Model
{
    protected $table = "categories";

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

}
