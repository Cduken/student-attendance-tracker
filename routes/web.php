<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Classes routes
    Route::resource('classes', ClassesController::class);

    // Students routes
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::resource('classes.students', StudentController::class)
        ->except(['index', 'create'])
        ->shallow();
    Route::get('/classes/{class}/students/create', [StudentController::class, 'create'])
        ->name('students.create');

    // Attendance routes
    Route::get('/attendance', [AttendanceController::class, 'globalIndex'])->name('attendance.global.index');
    Route::prefix('classes/{class}/attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.class.index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/', [AttendanceController::class, 'store'])->name('attendance.store');
    });
    Route::resource('attendances', AttendanceController::class)
        ->except(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';
