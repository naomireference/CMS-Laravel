<?php

use App\Post;
use App\User;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome'); //view
});

// Route::get('/contact','PostsController@contact');
// Route::get('post/{id}','PostsController@show_post');






//---------------------------------------------------------------------------------------------------
//ROUTING A CONTROLLER
/*
    Route::get('/post/{id}', 'PostsController@index'); //<Controller name>@<function>
*/
//---------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------
//Resource - can be used in creating special routes for create, delete, and update
/* run "php artisan route:list" 
    Route::resource('posts','PostsController' );
*/
//---------------------------------------------------------------------------------------------------




//---------------------------------------------------------------------------------------------------
// WITH PARAMETER
/*
    Route::get('/post/{id}', function($id){
        return "This is post number ".$id;
    });
*/
//---------------------------------------------------------------------------------------------------



//---------------------------------------------------------------------------------------------------
// NAMING A ROUTE
/*
= run "php artisan route:list" to show all routes
= Result:
+--------+----------+---------------------+------------+---------+--------------
| Domain | Method   | URI                 | Name       | Action  | Middleware
+--------+----------+---------------------+------------+---------+--------------
|        | GET|HEAD | /                   |            | Closure | web
|        | GET|HEAD | about               |            | Closure | web
|        | GET|HEAD | admin/posts/example | admin.home | Closure | web
|        | GET|HEAD | api/user            |            | Closure | api,auth:api
|        | GET|HEAD | contact             |            | Closure | web
|        | GET|HEAD | post/{id}           |            | Closure | web
+--------+----------+---------------------+------------+---------+--------------

    Route::get('admin/posts/example', array('as'=>'admin.home' , function(){
        $url = route('admin.home');
        return "this url is ". $url;
    }));

*/
//---------------------------------------------------------------------------------------------------




//---------------------------------------------------------------------------------------------------
//RAW SQL QUERIES
/*
    I. Inserting Data

        Route::get('/insert', function(){
            DB::insert('insert into posts(title, content) values(?,?)',['PHP with Laravel','Laravel is the best thing that has happened to PHP']);
        });

    II. Reading Data

            Route::get('/read', function(){
                $results = DB::select('select * from posts where id=?',[1]);

                foreach($results as $result){
                    return $result->title;
                }
            });

    III. Updating Data

            Route::get('/update', function(){
                $updated = DB::update('update posts set title="Update title" where id=?',[1]);

                return $updated;
            });

    IV. Deleting Data

            Route::get('/delete', function(){
                $deleted=DB::delete('delete from posts where id=?',[1]);

                return $deleted;
            });
*/
//---------------------------------------------------------------------------------------------------




//---------------------------------------------------------------------------------------------------
//DATABASE - ELOQUENT / Object Relational Model
/*
    * Before using eloquent, make sure to create a model:
        -> php artisan make:model Post

    * The model will be found in 'app/'. 
    * add the code: 'use App\Post' to import the model

    I. Reading Data
            //read all
            Route::get('/read',function(){
                $posts = Post::all();

                foreach($posts as $post){
                    return $post->title;
                }
            });

            //read specific
            Route::get('/find',function(){
                $post = Post::find(2);
                return $post->title;
            });
    
    II. Reading / Finding with Constraints
            Route::get('/findwhere',function(){
                $posts = Post::where('id',2)->orderBy('id', 'desc')->take(1)->get();

                return  $posts;
            });

    III. More ways to retrieve data
            //findOrFail(<parameter>) = Not Found Exceptions. Returns 404 Not Found of query does not exist 
            Route::get('/findmore', function(){
                $posts = Post::findOrFail(1);

                return $posts;
            });

    IV. Inserting / Saving Data
            Route::get('/basicinsert', function(){
                $post = new Post;

                $post->title = 'new Eloquent title insert';
                $post->content = 'Wow eloquent is really cool, look at this content';

                $post->save();
            });

    V. Creating Data and Configuring Mass Assignment
            // Step 1: add this to Post.php: "protected $fillable = ['title', 'content'];"
            //Route:
            Route::get('create', function(){
                Post::create(['title'=>'the create method', 'content'=>'WOW I am learning a lot with Edwin Diaz']);
            });

    VI. Updating with Eloquent
            Route::get('/update', function(){
                Post::where('id',2)->where('is_admin',0)->update(['title'=>'NEW PHP TITLE', 'content'=>'I love my instructor Edwin']);
            });

    VII. Deleting Data
            //First way:
            Route::get('/delete', function(){
                $post = Post::find(2);

                $post->delete();
            });

            //Second way:
            Route::get('/delete2',function(){
                Post::destroy(3);
            });

            //Deleting multiple data
            Route::get('/delete', function(){
                Post::destroy([4,5]);
            });

            //Deleting using query
            Route::get('/delete', function(){
                Post::where('is_admin',0)->delete();
            });

    VIII. Soft Deleting / Trashing
            //Result: adds a timestamp to 'deleted_at' column, but it won't show when you query it.
            
            //Step 1: Add these codes to 'Post.php':
            -> use Illuminate\Database\Eloquent\SoftDeletes;
            -> use SoftDeletes;
            -> protected $date = ['deleted_at'];

            //Step 2: Perform 'php artisan make:migration add_deleted_at_column_to_posts_tables --table=posts'
            //Step 3: Open '\database\migrations\2020_01_03_071556_add_deleted_at_column_to_posts_tables.php'
            //Step 4: Add the following codes:
            -> $table->softDeletes();
            -> $table->dropColumn('deleted_at');
            //Route:
            Route::get('/softdelete', function(){
                Post::find(6)->delete();
            });

    IX. Retrieving deleted / trashed records
            //Result: shows the trashed records
            //First way:
            Route::get('/readsoftdelete', function(){
                $post = Post::withTrashed()->where('id',6)->get();
                return $post;
            });

            //Second way:
            Route::get('/readsoftdelete', function(){
                $post = Post::onlyTrashed()->where('is_admin',0)->get();
                return $post;
            });

    X. Restoring deleted / trashed records
            //Result: makes value at 'deleted_at' column into 'NULL'
            Route::get('/restore',function(){
                Post::withTrashed()->where('is_admin',0)->restore();
            });
    
    XI. Deleting a record permanently
            Route::get('/forcedelete', function(){
                Post::onlyTrashed()->where('is_admin',0)->forceDelete();
            });

*/

//---------------------------------------------------------------------------------------------------




//---------------------------------------------------------------------------------------------------
//ELOQUENT Relationships
/*
    I. One to One Relationship
            //Step 1:
            ->  Add on database/migrations/2020_01_03_011559_create_posts_table.php:
              '$table->integer('user_id')->unsigned();'
            
            //Step 2:
            ->  Add on app/User.php
                public function post(){
                    return $this->hasOne('App\Post'); //looks for 'users_id' columns
                }

            //Route:
            Route::get('/user/{id}/post', function($id){
                // return User::find($id)->post
                return User::find($id)->post->title;
            });
    
    II. The Inverse Relationship for One to One
            -> pulls out the user of a post
            //Step 1:
            -> Add this code on 'app/Post.php':
                public function user(){
                    return $this->belongsTo('App\User');
                }

            //Route:
            Route::get('/post/{id}/user', function($id){
                return Post::find($id)->user->name;
            });
    
    III. One to Many Relationship
            //Step 1:
            -> Add this code on 'app/User.php':
                public function posts(){
                    return $this->hasMany('App\Post');
                } 
            
            //Route:
            Route::get('/posts', function(){
                $user = User::find(1);

                foreach($user->posts as $post){
                    echo $post->title ."</br>";
                }
            });

    IV. Many to Many Relationship
            //Step 1:
            -> Create migration and model for 'Role':
                php artisan make:model Role -m
                    -> result: Model created successfully.
                               Created Migration: 2020_01_09_080015_create_roles_table

            //Step 2:
            -> Create pivot table combining user and role table:
                php artisan make:migration create_users_roles_table --create=role_user
                    -> result: Created Migration: 2020_01_09_080359_create_users_roles_table
            
            //Step 3:
            -> Add this code on 'database/migrations/2020_01_09_080015_create_roles_table.php':
                $table->string('name');

            //Step 4:
            -> Add this code on 'database/migrations/2020_01_09_080359_create_users_roles_table.php':
                $table->integer('user_id');
                $table->integer('role_id');
            
            //Step 5:
            -> start migrating:
                php artisan migrate
            
            //Step 6:
            -> Add this code to 'app/User.php':
                public function roles(){
                    return $this->belongsToMany('App\Role');
                }
            
            //Route:
            -> First Way:
                Route::get('/user/{id}/role', function($id){
                    $user = User::find($id);
                    foreach($user->roles as $role){
                        echo $role->name;
                    }
                });
            -> Second Way:
                Route::get('/user/{id}/role', function($id){
                    $user = User::find($id)->roles()->orderBy('id', 'desc')->get();
                    return $user;
                });

                


*/
//---------------------------------------------------------------------------------------------------
