<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');

Route::controllers([
    //'users' => 'Backend\UserController'
]);

Route::group(['middleware' => ['auth']], function(){
    Route::get('/home', function () {
        return view('home');
    });
});

//protect route by role middleware - based on role
Route::group(['middleware' => ['auth', 'role:admin']], function(){

});

Route::post('/test',function(Request $request){

});

//protect route by authorize middleware - based on permission
//Route::group(['middleware' => ['auth', 'authorize']], function(){
Route::group(['prefix' => 'backend'], function(){
    Route::get('/users/data', 'Backend\UserController@data');
    Route::resource('users', 'Backend\UserController');

    Route::get('/roles/list', 'Backend\RoleController@getList');
    Route::resource('roles', 'Backend\RoleController');
    Route::resource('permissions', 'Backend\PermissionController');
    Route::get('/role_permission', 'Backend\RolePermissionController@index');
    Route::post('/role_permission', 'Backend\RolePermissionController@store');
});

use Illuminate\Http\Request;

Route::group([], function(){
    Route::get('/tasks', function () {
        $tasks = \App\Task::all();
        return View::make('task')->with('tasks',$tasks);
    });

    Route::get('/tasks/{task_id?}',function($task_id){
        $task = \App\Task::find($task_id);

        return response()->json($task);
    });

    Route::post('/tasks',function(Request $request){
        $task = \App\Task::create($request->all());

        return response()->json($task);
    });

    Route::put('/tasks/{task_id?}',function(Request $request,$task_id){
        $task = \App\Task::find($task_id);

        $task->task = $request->task;
        $task->description = $request->description;

        $task->save();

        return response()->json($task);
    });

    Route::delete('/tasks/{task_id?}',function($task_id){
        $task = \App\Task::destroy($task_id);

        return response()->json($task);
    });
});