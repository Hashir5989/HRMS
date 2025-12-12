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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('allowance_housing', 10, 2)->default(0)->after('salary');
            $table->decimal('allowance_medical', 10, 2)->default(0)->after('allowance_housing');
            $table->decimal('allowance_transport', 10, 2)->default(0)->after('allowance_medical');
            $table->decimal('deduction_tax', 10, 2)->default(0)->after('allowance_transport');
            $table->decimal('deduction_other', 10, 2)->default(0)->after('deduction_tax');
            $table->decimal('net_salary', 10, 2)->default(0)->after('deduction_other');
            $table->string('pay_frequency')->default('monthly')->after('net_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'allowance_housing',
                'allowance_medical',
                'allowance_transport',
                'deduction_tax',
                'deduction_other',
                'net_salary',
                'pay_frequency',
            ]);
        });
    }
};
