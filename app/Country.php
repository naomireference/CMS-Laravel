<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //gets posts through a specific country
    public function posts(){
        //return $this->hasManyThrough('<first table>', '<intermediate table where to get country id>');
        return $this->hasManyThrough('App\Post', 'App\User');
    }
}
