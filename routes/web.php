<?php

use App\Http\Controllers\JoinClassroomController;
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


Route::get('/admin/2fa',[\App\Http\Controllers\Admin\TwoFactorAuthenticationController::class, 'create'])->middleware('auth:admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//this folder for breeze if use fortify comment it and delete backend
//require __DIR__ . '/auth.php';

//Route::group([
//    'middleware' => ['auth'],
//    'prefix' => 'tt'
//], function(){});

Route::get('/plans', [\App\Http\Controllers\PlansController::class, 'index'])->name('plans');

Route::middleware(['auth'])->prefix('')->group(function () {

    Route::get('settings/{group}', [\App\Http\Controllers\SettingController::class, 'edit'])
        ->name('settings');
    Route::patch('settings/{group}', [\App\Http\Controllers\SettingController::class, 'update']);

    Route::prefix('/classrooms/trashed')
        ->as('classrooms.')
        ->controller(\App\Http\Controllers\ClassroomController::class)
        ->group(function () {
            Route::get('', 'trashed')->name('trashed');
            Route::put('/{classroom}', 'restore')->name('restore');
            Route::delete('/{classroom}', 'forceDeleted')->name('force-deleted');
        });

//    Route::resource('/classrooms', \App\Http\Controllers\ClassroomController::class)
//        ->parameters(['{classroom}' =>'{class}'])
//        ->name(
//            'index' , 'c.index'
//        );


    //two has the same name
    Route::get('/classrooms/{classroom}/join', [JoinClassroomController::class, 'create'])->name('classrooms.join');
    Route::post('/classrooms/{classroom}/join', [JoinClassroomController::class, 'store']);

    Route::get('/classrooms/{classroom}/chat', [\App\Http\Controllers\ClassroomController::class, 'chat'])->name('classrooms.chat');
    Route::resources([
        'topics' => \App\Http\Controllers\TopicController::class,
        'classrooms' => \App\Http\Controllers\ClassroomController::class,
    ]);

    Route::resource('classrooms.classworks', \App\Http\Controllers\ClassworkController::class);
    //        ->shallow();


    Route::get('classrooms/{classroom}/people', [\App\Http\Controllers\ClassroomPeopleController::class, 'index'])
        ->name('classrooms.people');
    Route::delete('classrooms/{classroom}/people', [\App\Http\Controllers\ClassroomPeopleController::class, 'destroy'])
        ->name('classrooms.people.destroy');

    Route::post('comments', [\App\Http\Controllers\CommentController::class, 'store'])
        ->name('comments.store');

    Route::post('classworks/{classwork}/submissions', [\App\Http\Controllers\SubmissionController::class, 'store'])
        ->name('submissions.store')
        ->middleware('can:submissions.create,classwork');
            //user policy
//        ->middleware('can:create,App\Models\Classwork');
    Route::get('submissions/{submission}/file', [\App\Http\Controllers\SubmissionController::class, 'file'])
        ->name('submissions.show');


    Route::get('qr-code', \App\Http\Controllers\ImageQRController::class);

    Route::get('/gate/{tt}', function ($tt) {
        dd(\Illuminate\Support\Facades\Gate::allows('classworks.view', \App\Models\Classwork::find(1)));
    })->where('tt', '.+');
});

