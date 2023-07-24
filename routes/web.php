<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Route::group([
//    'middleware' => ['auth'],
//    'prefix' => 'tt'
//], function(){});


Route::middleware(['auth'])->prefix('')->group(function(){

    Route::prefix('/classrooms/trashed')
        ->as('classrooms.')
        ->controller(\App\Http\Controllers\ClassroomController::class)
        ->group(function(){
            Route::get('','trashed')->name('trashed');
            Route::put('/{classroom}', 'restore')->name('restore');
            Route::delete('/{classroom}', 'forceDeleted')->name('force-deleted');
        });

//    Route::resource('/classrooms', \App\Http\Controllers\ClassroomController::class)
//        ->parameters(['{classroom}' =>'{class}'])
//        ->name(
//            'index' , 'c.index'
//        );

    Route::resources([
        'topics' => \App\Http\Controllers\TopicController::class,
        'classrooms' => \App\Http\Controllers\ClassroomController::class
    ]);

});

