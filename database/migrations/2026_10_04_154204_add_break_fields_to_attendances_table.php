<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('break_start')->nullable()->after('clock_out');
            $table->time('break_end')->nullable()->after('break_start');
            $table->text('break_reason')->nullable()->after('break_end');
            $table->integer('break_minutes')->default(0)->after('break_reason');
            $table->boolean('on_break')->default(false)->after('break_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['break_start', 'break_end', 'break_reason', 'break_minutes', 'on_break']);
        });
    }
};
