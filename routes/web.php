<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EvaluationFormController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CertificateController;

Route::get('/', function () {
    return view('welcome');
});

//check if logged in
Route::middleware('auth')->group(function () {
    Route::get('/profile/MyCertificates', [ProfileController::class, 'myCertificates'])->name('profile.mycertificates');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    route::get('/profile', [ProfileController::class, 'profile'])->name('profile.profile');
    Route::get('/profile/{id}', [ProfileController::class, 'view'])->name('profile.view');
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


//check role if super admin or admin
Route::middleware(['auth', 'checkRole:1,2'])->group(function () {

    //event creation and the users created event list
    route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/myEventlist', [EventController::class, 'myEventlist'])->name('event.myeventlist');

});

//check role if user
Route::middleware(['auth', 'checkRole:3'])->group(function () {
    Route::post('/register-admin', [ProfileController::class, 'registerAdmin'])->name('register.admin');
});


Route::middleware('auth')->group(function () {


    //overall event view and join
    route::resource('events', EventController::class);
    route::get('/events', [EventController::class, 'list'])->name('event.list');
    route::get('/event/{id}', [EventController::class, 'view'])->name('event.view');
    Route::post('/event/{id}/join', [EventController::class, 'join'])->name('event.join');
    Route::post('/event/{id}/participants/send-certificates', [EventController::class, 'sendCertificates'])->name('sendCertificates');

    
    
    

    //cert stuff 
    Route::get('/event/{id}/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/event/{id}/certificates/save', [CertificateController::class, 'saveCanvas']);
    Route::get('/event/{id}/certificates/load/{certId}', [CertificateController::class, 'loadCanvas']);
    Route::get('/event/{id}/certificates/get-id', [CertificateController::class, 'getCertificateId']);
    Route::get('/event/{id}/certificates/viewCert/{certId}', [CertificateController::class, 'viewImage'])->name('certificates.view');
    Route::get('/event/{id}/certificates/{certId}/show', [CertificateController::class, 'showCertificateImage'])->name('certificates.show');
});

//check if user joined the event
Route::middleware(['auth','checkUserJoinedEvent'])->group(function () {
    //Answer event form
    Route::get('/event/{id}/evaluation-form/{form}/take', [EvaluationFormController::class, 'take'])->name('evaluation-form.take');
    Route::post('/event/{id}/submit-evaluation', [EvaluationFormController::class, 'submit'])->name('evaluation-form.submit');

});

//checks the creator of event
Route::middleware(['auth', 'checkEventCreator'])->group(function () {

    //update event info
    route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    route::patch('/event/{id}/update', [EventController::class, 'update'])->name('event.update');

    Route::get('/event/{id}/pending-participants', [EventController::class, 'showParticipants'])->name('events.participants');
    Route::get('/event/{id}/participants', [EventController::class, 'showParticipantslist'])->name('events.participantslist');
    Route::post('/event/{id}/participants/{participant}/update', [EventController::class, 'updateParticipantStatus'])->name('participants.updateStatus');
    

    //eval forms
    Route::post('/event/{id}/evaluation-form', [EvaluationFormController::class, 'store'])->name('evaluation-forms.store');
    Route::put('/event/{id}/evaluation-form/{form}', [EvaluationFormController::class, 'update'])->name('evaluation-forms.update');
    Route::put('/events/{id}/evaluation-form/{form}/toggle', [EvaluationFormController::class, 'toggleActivation'])->name('evaluation-forms.toggle');
    

});
//check the event owner through form
Route::group(['middleware' => ['auth', 'checkFormOwner']], function() {
    // create question
    Route::get('/event/{id}/evaluation-form/{form}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/event/{id}/evaluation-forms/{form}/questions', [QuestionController::class, 'store'])->name('questions.store');

    // update question
    Route::get('/event/{id}/evaluation-form/{form}/questions/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/event/{id}/evaluation-form/{form}/questions/update', [QuestionController::class, 'update'])->name('questions.update');
});

//super admin stuff
route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->middleware(['auth','super_admin'])->name('super_admin.dashboard');

//admin stuff
route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware(['auth','admin'])->name('admin.dashboard');

//user stuff
route::get('/user/dashboard', [UserController::class, 'index'])->middleware(['auth','user'])->name('user.dashboard');