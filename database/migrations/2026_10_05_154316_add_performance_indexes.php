<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('date');
            $table->index('status');
            $table->index(['date', 'status']);
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status');
            $table->index('assigned_user_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['date', 'status']);
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['assigned_user_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
