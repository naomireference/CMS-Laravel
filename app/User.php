<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*Add on: database/migrations/2020_01_03_011559_create_posts_table.php
        '$table->integer('user_id')->unsigned();'
    */ 

    /*For One to One relationship */
    public function post(){
        return $this->hasOne('App\Post'); //looks for 'users_id' columns
    }

    /*For One to Many relationship */
    public function posts(){
        return $this->hasMany('App\Post');
    }

    /*For Many to Many relationship */
    public function roles(){
        return $this->belongsToMany('App\Role')->withPivot('created_at');

        // To customize tables name and columns follow the format below:
        // return $this->belongsToMany('App\Role', 'user_roles','user_id','role_id');
    }

    public function photos(){
        return $this->morphMany('App\Photo', 'imageable');
    }


}
