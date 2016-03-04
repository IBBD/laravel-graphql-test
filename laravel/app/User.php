<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    public function books()
    {
        //echo '----------';
        return $this->belongsToMany('App\Book', 'user_book');
        //return $this->belongsToMany('App\Book', 'user_book', 'user_id', 'book_id');
    }

}
