<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {

    route::get('/profile', [ProfileController::class, 'profile'])->name('profile.profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';

route::get('super_admin/dashboard',[SuperAdminController::class,'index'])->
    middleware(['auth','super_admin']);
route::get('admin/dashboard',[AdminController::class,'index'])->
    middleware(['auth','admin']);
route::get('user/dashboard',[UserController::class,'index'])->
    middleware(['auth','user']);

route::get('/unauthorized', function () {
        return view('unauthorized');
    })->name('unauthorized');

//event stuff

Route::middleware(['auth', 'checkRole:1,2'])->group(function () {
    route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/myEventlist', [EventController::class, 'myEventlist'])->name('event.myeventlist');
});

Route::middleware('auth')->group(function () {
    route::resource('events', EventController::class);
    route::get('/events', [EventController::class, 'list'])->name('event.list');
    route::get('/event/{id}', [EventController::class, 'view'])->name('event.view');
    route::post('/event/join/{id}', [EventController::class, 'join'])->name('event.join');
});



Route::middleware(['auth', 'checkEventCreator'])->group(function () {
    
    route::get('/event/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    route::patch('/event/update/{id}', [EventController::class, 'update'])->name('event.update');
});

//super admin stuff
route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->middleware(['auth','super_admin'])->name('super_admin.dashboard');

//admin stuff
route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['auth','admin'])->name('admin.dashboard');

//user stuff
route::get('/user/dashboard', [UserController::class, 'index'])->middleware(['auth','user'])->name('user.dashboard');