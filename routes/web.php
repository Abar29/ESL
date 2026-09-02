<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\TeacherController as StudentTeacherController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Teacher\AvailabilityController;
use App\Http\Controllers\Teacher\BookingController as TeacherBookingController;
use App\Http\Controllers\Teacher\HistoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeacherApprovalController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role->value) {
        'admin' => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => Inertia::render('Dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture');
    Route::get('/profile/pic', [ProfileController::class, 'getPicture'])->name('profile.pic');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Routes
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Student/Dashboard'))->name('dashboard');
    Route::get('/teachers', [StudentTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacher}', [StudentTeacherController::class, 'show'])->name('teachers.show');
    Route::get('/bookings', [StudentBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{slot}', [StudentBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [StudentBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [StudentBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// Teacher Routes
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Teacher/Dashboard'))->name('dashboard');
    Route::get('/profile', [TeacherProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/data', [TeacherProfileController::class, 'data'])->name('profile.data');
    Route::put('/profile', [TeacherProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [TeacherProfileController::class, 'updatePicture'])->name('profile.picture');
    Route::post('/profile/certificates', [TeacherProfileController::class, 'storeCertificate'])->name('profile.certificates.store');
    Route::delete('/profile/certificates/{certificate}', [TeacherProfileController::class, 'destroyCertificate'])->name('profile.certificates.destroy');
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::patch('/availability/{slot}', [AvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{slot}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
    Route::get('/bookings', [TeacherBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TeacherBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/accept', [TeacherBookingController::class, 'accept'])->name('bookings.accept');
    Route::post('/bookings/{booking}/decline', [TeacherBookingController::class, 'decline'])->name('bookings.decline');
    Route::post('/bookings/{booking}/complete', [TeacherBookingController::class, 'complete'])->name('bookings.complete');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/teacher-approvals', [TeacherApprovalController::class, 'index'])->name('teacher-approvals.index');
    Route::post('/teacher-approvals/{teacher}/approve', [TeacherApprovalController::class, 'approve'])->name('teacher-approvals.approve');
    Route::post('/teacher-approvals/{teacher}/reject', [TeacherApprovalController::class, 'reject'])->name('teacher-approvals.reject');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
});

require __DIR__.'/auth.php';
