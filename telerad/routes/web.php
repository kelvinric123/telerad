<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrthancController;
use Illuminate\Support\Facades\Route;

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
});

require __DIR__.'/auth.php';
