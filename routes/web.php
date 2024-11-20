<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;

// Welcome & Auth Routes
Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

// Dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Classroom Routes
    Route::resource('classrooms', ClassroomController::class);
    Route::get('classrooms/{classroom}/students', [ClassroomController::class, 'getStudents'])
        ->name('classrooms.students');
    Route::get('classrooms/{classroom}/teachers', [ClassroomController::class, 'getTeachers'])
        ->name('classrooms.teachers');

    // Teacher Routes
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers/classroom/{classroom}', [TeacherController::class, 'getByClassroom'])
        ->name('teachers.by.classroom');

    // Student Routes
    Route::resource('students', StudentController::class);
    Route::get('students/classroom/{classroom}', [StudentController::class, 'getByClassroom'])
        ->name('students.by.classroom');
});
