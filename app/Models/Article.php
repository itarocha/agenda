<?php

namespace App\Models;

use Model;

class Article extends Model {

    protected $table = 'articles';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

}

// $article = \App\Models\Article::with(['user','category'])->first();
//retrieve user name
// $article->user->user_name
//
// //retrieve category name
// $article->category->category_name

// $categories = \App\Models\Category::with('articles')->get();
//
// $users = \App\Models\Category::with('users')->get();

// TABELA NETA
//  return $this->hasManyThrough('GamePlatform','GameOptions','id','game_id');
