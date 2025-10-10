<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Employee Management
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);

    // Department Management
    Route::resource('departments', App\Http\Controllers\DepartmentController::class);

    // Team Management
    Route::resource('teams', App\Http\Controllers\TeamController::class);

    // Project Management
    Route::resource('projects', App\Http\Controllers\ProjectController::class);

    // Task Management
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
    Route::put('tasks/{task}/status', [App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Leave Management
    Route::resource('leaves', App\Http\Controllers\LeaveApplicationController::class)->parameters(['leaves' => 'leaf']);

    // Attendance Management
    Route::get('attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/clock-in', [App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('attendance/clock-out', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clockOut');

    // File Management
    Route::resource('files', App\Http\Controllers\FileController::class);
    Route::get('files/{file}/download', [App\Http\Controllers\FileController::class, 'download'])->name('files.download');

    // Holiday Management
    Route::resource('holidays', App\Http\Controllers\HolidayController::class);

    // Meeting Management
    Route::resource('meetings', App\Http\Controllers\MeetingController::class);

    // Designation Management
    Route::resource('designations', App\Http\Controllers\DesignationController::class);
});
