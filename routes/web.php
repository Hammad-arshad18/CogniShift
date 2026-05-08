<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Sample premium feature route
    Route::get('/premium-feature', function () {
        return "You have access to the premium feature!";
    })->middleware('plan_tier:premium')->name('premium.feature');

    // HR Routes
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::resource('payroll', \App\Http\Controllers\PayrollController::class);
    
    // Performance Reviews with AI
    Route::get('performance-reviews', [\App\Http\Controllers\PerformanceReviewController::class, 'index'])->name('reviews.index');
    Route::post('performance-reviews/generate', [\App\Http\Controllers\PerformanceReviewController::class, 'generate'])->name('reviews.generate');
});

require __DIR__.'/auth.php';
