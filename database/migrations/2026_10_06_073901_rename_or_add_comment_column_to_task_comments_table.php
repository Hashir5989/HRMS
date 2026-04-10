<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            if (Schema::hasColumn('task_comments', 'body') && ! Schema::hasColumn('task_comments', 'comment')) {
                $table->renameColumn('body', 'comment');
            } elseif (! Schema::hasColumn('task_comments', 'comment')) {
                $table->text('comment')->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            if (Schema::hasColumn('task_comments', 'comment') && ! Schema::hasColumn('task_comments', 'body')) {
                $table->renameColumn('comment', 'body');
            }
        });
    }
};
