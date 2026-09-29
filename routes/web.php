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
    Route::post('tasks/{task}/comments', [App\Http\Controllers\TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::put('comments/{comment}', [App\Http\Controllers\TaskCommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [App\Http\Controllers\TaskCommentController::class, 'destroy'])->name('comments.destroy');

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

    // Chat / Messaging
    Route::get('chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/{conversation}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('chat/message', [App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::post('chat/start', [App\Http\Controllers\ChatController::class, 'startConversation'])->name('chat.start');

    // Notifications
    Route::get('notifications', [App\Http\Controllers\ChatController::class, 'notifications'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\ChatController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('api/notifications', [App\Http\Controllers\ChatController::class, 'getNotificationsJson'])->name('notifications.json');
    Route::get('api/chat/{conversation}/messages', function (\App\Models\Conversation $conversation, \Illuminate\Http\Request $request) {
        if (!$conversation->participants->contains(auth()->id())) abort(403);
        $after = $request->query('after', 0);
        return $conversation->messages()->with('user')->where('id', '>', $after)->get()->map(fn($m) => [
            'id' => $m->id,
            'user_id' => $m->user_id,
            'user_name' => $m->user->name,
            'body' => $m->body,
            'created_at' => $m->created_at->format('h:i A'),
        ]);
    });
});
