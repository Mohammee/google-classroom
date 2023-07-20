<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/download', 'download');

Route::get('/tt/{name}/{d?}', function ($name, $default = null){
    dd(\route('classrooms.show', ['a' => 1,2,3], false));
    dd($name, $default);
})->where('name', '.+')->where('d', 'yes|no');
Route::get('/', function () {
    return view('welcome');
});


//dd('tete');
//Route::get('/classrooms', [\App\Http\Controllers\Classroom::class, 'index']);
//Route::get('/classrooms/create', [\App\Http\Controllers\Classroom::class, 'create']);
//Route::get('/classrooms/{classroom:code}', [\App\Http\Controllers\Classroom::class, 'show'])
//->where('classroom', '\d+');
//Route::post('/classrooms', [\App\Http\Controllers\Classroom::class, 'store']);

Route::resource('/classrooms', \App\Http\Controllers\ClassroomController::class)
->parameters(['{classroom}' =>'{class}'])
->name(
    'index' , 'c.index'
);


