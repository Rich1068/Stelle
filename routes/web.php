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
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on the user's role
        if ($user->role_id == 1) {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->role_id == 2) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 3) {
            return redirect()->route('user.dashboard');
        }
    }

    // If the user is not authenticated, redirect to the login page
    return redirect()->route('login');
});
Route::get('/auth/google/redirect', [GoogleController::class, 'googlepage'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'googlecallback'])->name('google.callback');

Route::get('/account-deleted', [ProfileController::class, 'accountDeleted'])->name('account.deleted');

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
    Route::get('/get-provinces/{regionId}', [ProfileController::class, 'getProvinces']);
    Route::get('/help', [UserController::class, 'help'])->name('help.page');
});

require __DIR__.'/auth.php';


//super admin
Route::middleware(['auth','super_admin'])->group(function () {

    route::get('/super-admin/dashboard', [SuperAdminController::class, 'index'])->middleware('verified')->name('super_admin.dashboard');
    route::get('/super-admin/userlist',[SuperAdminController::class,'userlist'])->name('super_admin.userlist');
    route::get('/super-admin/viewRequestingAdmins',[SuperAdminController::class,'viewRequestingAdmins'])->name('super_admin.requestingAdmins');
    Route::post('/handle-admin-request/{id}/{action}', [SuperAdminController::class, 'handleAdminRequest'])->name('super_admin.adminRequest');
    Route::get('/super-admin/users/create', [SuperAdminController::class, 'usercreate'])->name('superadmin.usercreate');
    Route::post('/super-admin/users', [SuperAdminController::class, 'storeuser'])->name('superadmin.storeuser');
    Route::get('/super-admin/users/edit/{id}', [ProfileController::class, 'superadmin_edit'])->name('superadmin.editProfile');
    Route::patch('/super-admin/users/update/{id}', [ProfileController::class, 'superadmin_update'])->name('superadmin.updateProfile');
    Route::delete('/super-admin/users/delete/{id}', [ProfileController::class, 'superadmin_destroy'])->name('superadmin.destroyUser');
    Route::patch('/super-admin/users/role-update/{id}', [ProfileController::class, 'updateRole'])->name('role.update');
    Route::get('/super-admin/events-data', [SuperAdminController::class, 'getEventsData']);
    Route::get('/super-admin/users-data', [SuperAdminController::class, 'getUsersDataByYear']);
    Route::get('/super-admin/all-events', [SuperAdminController::class, 'allEventList'])->name('superadmin.eventlist');
    Route::get('/super-admin/dashboard/participants-per-event', [SuperAdminController::class, 'getPaginatedParticipantsPerEvent'])->name('superadmin.participants.per.event');
    Route::patch('/super-admin/event/{id}/deactivate', [EventController::class, 'deactivate'])->name('event.deactivate');
    Route::patch('/super-admin/event/{id}/recover', [EventController::class, 'recover'])->name('event.recover');
});

//admin
Route::middleware(['auth','admin'])->group(function () {

    route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('verified')->name('admin.dashboard');
    Route::get('/admin/dashboard/get-events-data', [AdminController::class, 'getAdminCreatedEventsData'])->name('admin.getEventsData');
    Route::get('/admin/dashboard/participants-per-event', [AdminController::class, 'getPaginatedParticipantsPerEvent'])->name('admin.participants.per.event');
    
});
//user
Route::middleware(['auth','user'])->group(function () {
    route::get('/user/dashboard', [UserController::class, 'index'])->middleware('verified')->name('user.dashboard');
    Route::get('/user/dashboard/events-data', [UserController::class, 'getEventsData']);
});

route::get('/unauthorized', function () {
        return view('unauthorized');
    })->name('unauthorized');


//check role if super admin or admin
Route::middleware(['auth', 'checkRole:1,2'])->group(function () {
    Route::get('/profile/{id}/events-data', [ProfileController::class, 'getEventsData'])->name('profile.eventsData');
    Route::get('/profile/{id}/events-created-data', [ProfileController::class, 'getEventsCreatedData'])->name('profile.getAdminCreatedEventsData');
    Route::get('/profile/{id}/events-joined-data', [ProfileController::class, 'getEventsJoinedData'])->name('profile.getAdminJoinedEventsData');
    Route::patch('/event/{id}/deactivate', [EventController::class, 'adminDeactivate'])->name('admin.event.deactivate')->middleware('checkEventCreator');
    //event creation and the myeventlist
    route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/event/myEventlist', [EventController::class, 'myEventlist'])->name('event.myeventlist');

    //new Eval
    Route::get('/evaluation-forms', [EvaluationFormController::class, 'evalList'])->name('evaluation.evaluationlist');
    Route::post('/evaluation-forms/{id}/duplicate', [EvaluationFormController::class, 'duplicate'])->name('evaluation-forms.duplicate');
    Route::get('/evaluation-forms/create', [EvaluationFormController::class, 'create'])->name('evaluation-forms.create');
    Route::post('/evaluation-forms/store', [EvaluationFormController::class, 'store'])->name('evaluation-forms.store');
    Route::get('/evaluation-forms/{id}/edit', [EvaluationFormController::class, 'edit'])->name('evaluation-forms.edit');
    Route::put('/evaluation-forms/{id}/update', [EvaluationFormController::class, 'update'])->name('evaluation-forms.update');
    Route::patch('/evaluation-forms/{id}/deactivate', [EvaluationFormController::class, 'deactivate'])->name('evaluation-forms.deactivate');

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




Route::middleware('auth')->group(function () {


    //overall event view and join
    route::resource('events', EventController::class);
    route::get('/events', [EventController::class, 'list'])->name('event.list');
    route::get('/event/{id}', [EventController::class, 'view'])->name('event.view');
    Route::post('/event/{id}/join', [EventController::class, 'join'])->name('event.join');
    Route::post('/event/{id}/participants/send-certificates', [CertificateController::class, 'sendCertificates'])->name('sendCertificates');
    Route::get('/event/{id}/get-participants', [EventController::class, 'getParticipants']);

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


