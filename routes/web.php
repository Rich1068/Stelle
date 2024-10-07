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
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('auth/google/redirect', [GoogleController::class, 'googlepage'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'googlecallback'])->name('google.callback');

//check if logged in
Route::middleware('auth')->group(function () {
    Route::get('/get-events', [EventController::class, 'getCalendarEvents'])->name('events.get');
    Route::get('/get-adminevents', [EventController::class, 'getAdminOnlyEvents'])->name('adminevents.get');
    Route::get('/profile/MyCertificates', [ProfileController::class, 'myCertificates'])->name('profile.mycertificates');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    route::get('/profile', [ProfileController::class, 'profile'])->name('profile.profile');
    Route::get('/profile/{id}', [ProfileController::class, 'view'])->name('profile.view');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';


//super admin
Route::middleware(['auth','super_admin'])->group(function () {

    route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->middleware('verified')->name('super_admin.dashboard');
    route::get('/super_admin/userlist',[SuperAdminController::class,'userlist'])->name('super_admin.userlist');
    route::get('/super_admin/viewRequestingAdmins',[SuperAdminController::class,'viewRequestingAdmins'])->name('super_admin.requestingAdmins');
    Route::post('/handle-admin-request/{id}/{action}', [SuperAdminController::class, 'handleAdminRequest'])->name('super_admin.adminRequest');
    Route::get('/super_admin/users/create', [SuperAdminController::class, 'usercreate'])->name('superadmin.usercreate');
    Route::post('/super_admin/users', [SuperAdminController::class, 'storeuser'])->name('superadmin.storeuser');
   
});

//admin
Route::middleware(['auth','admin'])->group(function () {

    route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('verified')->name('admin.dashboard');

});

//user
Route::middleware(['auth','user'])->group(function () {

    route::get('/user/dashboard', [UserController::class, 'index'])->middleware('verified')->name('user.dashboard');

});

route::get('/unauthorized', function () {
        return view('unauthorized');
    })->name('unauthorized');


//check role if super admin or admin
Route::middleware(['auth', 'checkRole:1,2'])->group(function () {

    //event creation and the users created event list
    route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/myEventlist', [EventController::class, 'myEventlist'])->name('event.myeventlist');

});




Route::middleware('auth')->group(function () {


    //overall event view and join
    route::resource('events', EventController::class);
    route::get('/events', [EventController::class, 'list'])->name('event.list');
    route::get('/event/{id}', [EventController::class, 'view'])->name('event.view');
    Route::post('/event/{id}/join', [EventController::class, 'join'])->name('event.join');
    Route::post('/event/{id}/participants/send-certificates', [CertificateController::class, 'sendCertificates'])->name('sendCertificates');
    Route::get('/event/{id}/get-participants', [EventController::class, 'getParticipants']);

    //new Eval
    Route::get('/evaluation-forms', [EvaluationFormController::class, 'evalList'])->name('evaluation.evaluationlist');

    Route::get('/evaluation-forms/create', [EvaluationFormController::class, 'create'])->name('evaluation-forms.create');
    Route::post('/evaluation-forms/store', [EvaluationFormController::class, 'store'])->name('evaluation-forms.store');
    Route::get('/evaluation-forms/{id}/edit', [EvaluationFormController::class, 'edit'])->name('evaluation-forms.edit');
    Route::put('/evaluation-forms/{id}/update', [EvaluationFormController::class, 'update'])->name('evaluation-forms.update');
    Route::patch('evaluation-forms/{id}/deactivate', [EvaluationFormController::class, 'deactivate'])->name('evaluation-forms.deactivate');

    //new cert
    Route::get('/certificate-list', [CertificateController::class, 'certlist'])->name('certificate.list');

    Route::get('/certificates/create/{certificateId?}', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates/save', [CertificateController::class, 'saveCanvas'])->name('certificates.save');

    Route::get('/certificates/{id}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{id}', [CertificateController::class, 'update'])->name('certificates.update');

    Route::get('/certificates/{id}/load', [CertificateController::class, 'loadCanvas'])->name('certificates.load');
    Route::get('/certificates/get', [CertificateController::class, 'getTemplates'])->name('certificates.templates');

    Route::patch('/certificates/{id}/deactivate', [CertificateController::class, 'deactivate'])->name('certificates.deactivate');

    Route::post('/certificate-template/save', [CertificateController::class, 'event_saveCanvas_asTemplate']);
    
    //cert stuff 
    Route::post('/event/{id}/certificates/saveImage', [CertificateController::class, 'saveImage'])->name('certificates.saveImage');
    Route::get('/event/{id}/certificates/getDesign', [CertificateController::class, 'getCertificateDesign']);
    Route::get('/event/{id}/certificates/create', [CertificateController::class, 'event_create'])->name('event_certificates.create');
    Route::post('/event/{id}/certificates/save', [CertificateController::class, 'event_saveCanvas']);
    Route::get('/event/{id}/certificates/load/{certId}', [CertificateController::class, 'event_loadCanvas']);
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
    Route::get('/event/{id}/pending-participants', [EventController::class, 'showPendingParticipants'])->name('events.pendingparticipants');
    Route::post('/event/{id}/participants/{participant}/update', [EventController::class, 'updateParticipantStatus'])->name('participants.updateStatus');
    Route::get('/event/{id}/evaluation-results/', [EvaluationFormController::class, 'showEvaluationResults'])->name('evaluation.results');

    

    //eval forms
    Route::get('/event/{id}/event-evaluation-forms/create', [EvaluationFormController::class, 'event_create'])->name('event-evaluation-forms.create');
    Route::post('/event/{id}/event-evaluation-forms/create', [EvaluationFormController::class, 'event_store'])->name('event-evaluation-forms.store');

    Route::get('/event/{id}/evaluation-forms/{formId}/edit', [EvaluationFormController::class, 'event_edit'])->name('event-evaluation-forms.edit');
    Route::put('/event/{id}/evaluation-forms/{formId}/update', [EvaluationFormController::class, 'event_update'])->name('event-evaluation-forms.update');

    Route::post('/event/{id}/event-evaluation-forms/use-existing', [EvaluationFormController::class, 'useExistingForm'])->name('event-evaluation-forms.use-existing');
    
    Route::put('/events/{id}/evaluation-form/{form}/toggle', [EvaluationFormController::class, 'toggleActivation'])->name('evaluation-forms.toggle');
    

});

//check role if user
Route::middleware(['auth', 'checkRole:3'])->group(function () {
    Route::post('/register-admin', [ProfileController::class, 'registerAdmin'])->name('register.admin');
});
//check the event owner through form
Route::group(['middleware' => ['auth', 'checkFormOwner']], function() {
    // create question
    // Route::get('/event/{id}/evaluation-form/{form}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    // Route::post('/event/{id}/evaluation-forms/{form}/questions', [QuestionController::class, 'store'])->name('questions.store');

    // // update question
    // Route::get('/event/{id}/evaluation-form/{form}/questions/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    // Route::put('/event/{id}/evaluation-form/{form}/questions/update', [QuestionController::class, 'update'])->name('questions.update');
});

