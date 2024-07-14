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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

//event stuff
Route::resource('events', EventController::class)->middleware('auth');
route::get('/event/create', [EventController::class, 'create'])->name('event.create');
//super admin stuff
route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->middleware(['auth','super_admin'])->name('super_admin.dashboard');

//admin stuff
route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['auth','admin'])->name('admin.dashboard');

//user stuff
route::get('/user/dashboard', [UserController::class, 'index'])->middleware(['auth','user'])->name('user.dashboard');