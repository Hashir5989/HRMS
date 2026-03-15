<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Employee Management
    Route::resource('employees', EmployeeController::class);

    // Department Management
    Route::resource('departments', DepartmentController::class);

    // Team Management
    Route::resource('teams', TeamController::class);

    // Project Management
    Route::resource('projects', ProjectController::class);

    // Task Management
    Route::resource('tasks', TaskController::class);
    Route::put('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::put('comments/{comment}', [TaskCommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [TaskCommentController::class, 'destroy'])->name('comments.destroy');

    // Leave Management
    Route::resource('leaves', LeaveApplicationController::class)->parameters(['leaves' => 'leaf']);

    // Attendance Management
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('attendance/start-break', [AttendanceController::class, 'startBreak'])->name('attendance.startBreak');
    Route::post('attendance/end-break', [AttendanceController::class, 'endBreak'])->name('attendance.endBreak');

    // ERP Payroll & Salary Management (Restricted to Super Admin & HR in Controller)
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll/{employee}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('payroll/{employee}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::get('payroll/{employee}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');

    // File Management
    Route::resource('files', FileController::class);
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');

    // Holiday Management
    Route::resource('holidays', HolidayController::class);

    // Meeting Management
    Route::resource('meetings', MeetingController::class);

    // Designation Management
    Route::resource('designations', DesignationController::class);

    // Chat / Messaging
    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('chat/message', [ChatController::class, 'store'])->name('chat.store');
    Route::post('chat/start', [ChatController::class, 'startConversation'])->name('chat.start');

    // Notifications
    Route::get('notifications', [ChatController::class, 'notifications'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [ChatController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('api/notifications', [ChatController::class, 'getNotificationsJson'])->name('notifications.json');
    Route::get('api/chat/{conversation}/messages', function (Conversation $conversation, Request $request) {
        if (! $conversation->participants->contains(auth()->id())) {
            abort(403);
        }
        $after = $request->query('after', 0);

        return $conversation->messages()->with('user')->where('id', '>', $after)->get()->map(fn ($m) => [
            'id' => $m->id,
            'user_id' => $m->user_id,
            'user_name' => $m->user->name,
            'body' => $m->body,
            'created_at' => $m->created_at->format('h:i A'),
        ]);
    });

    // Account & System Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});
