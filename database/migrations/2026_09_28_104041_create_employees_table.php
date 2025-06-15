<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_photo')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('team_lead_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('joining_date')->nullable();
            $table->string('employment_type')->nullable(); // full-time, part-time, contract
            $table->string('status')->default('active'); // active, inactive, terminated, on_leave
            $table->decimal('salary', 10, 2)->nullable();
            
            $table->json('emergency_contact')->nullable(); // name, relationship, phone
            $table->json('bank_details')->nullable(); // bank_name, account_no, ifsc
            $table->json('documents')->nullable(); // array of file paths
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
