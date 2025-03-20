<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrthancController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\HospitalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Orthanc Routes
    Route::get('/studies', [OrthancController::class, 'index'])->name('studies.index');
    Route::get('/studies/{studyId}', [OrthancController::class, 'show'])->name('studies.show');
    Route::get('/ohif/{studyId}', [OrthancController::class, 'launchOhif'])->name('ohif.launch');
    
    // Patients Routes
    Route::get('/patients', [App\Http\Controllers\PatientsController::class, 'index'])->name('patients.index');
    Route::get('/patients/{id}', [App\Http\Controllers\PatientsController::class, 'show'])->name('patients.show');
    Route::get('/patients/{id}/edit', [App\Http\Controllers\PatientsController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}', [App\Http\Controllers\PatientsController::class, 'update'])->name('patients.update');
    Route::get('/patients-sync', [App\Http\Controllers\PatientsController::class, 'sync'])->name('patients.sync');
    
    // Report Routes
    Route::get('/studies/{studyId}/reports/create', [App\Http\Controllers\StudyReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [App\Http\Controllers\StudyReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}', [App\Http\Controllers\StudyReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{id}/edit', [App\Http\Controllers\StudyReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{id}', [App\Http\Controllers\StudyReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{id}', [App\Http\Controllers\StudyReportController::class, 'destroy'])->name('reports.destroy');
    
    // Report Templates Routes
    Route::resource('report-templates', App\Http\Controllers\ReportTemplateController::class)->except(['show']);
    Route::get('/report-templates/get-templates', [App\Http\Controllers\ReportTemplateController::class, 'getTemplates'])->name('report-templates.get-templates');
    
    // Role Management Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/users/{user}/roles', [RoleController::class, 'assignRole'])->name('users.roles.assign');
    Route::delete('/users/{user}/roles', [RoleController::class, 'removeRole'])->name('users.roles.remove');
    
    // Hospital Management Routes
    Route::resource('hospitals', HospitalController::class);
    Route::get('/hospital/users', [HospitalController::class, 'manageUsers'])->name('hospitals.manage-users');
    Route::get('/hospital/users/create', [HospitalController::class, 'createUser'])->name('hospitals.create-user');
    Route::post('/hospital/users', [HospitalController::class, 'storeUser'])->name('hospitals.store-user');
    Route::get('/hospital/profile', [HospitalController::class, 'editHospitalProfile'])->name('hospitals.edit-profile');
    Route::put('/hospital/profile', [HospitalController::class, 'updateHospitalProfile'])->name('hospitals.update-profile');
});

require __DIR__.'/auth.php';
