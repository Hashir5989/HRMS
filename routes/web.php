<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('departments', App\Http\Controllers\DepartmentController::class);
    Route::resource('teams', App\Http\Controllers\TeamController::class);
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
});
