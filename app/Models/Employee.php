<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name',
        'profile_photo', 'phone', 'date_of_birth', 'gender', 'address',
        'department_id', 'designation_id', 'team_id', 'manager_id', 'team_lead_id',
        'joining_date', 'employment_type', 'status', 'salary',
        'allowance_housing', 'allowance_medical', 'allowance_transport',
        'deduction_tax', 'deduction_other', 'net_salary', 'pay_frequency',
        'emergency_contact', 'bank_details', 'documents',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'emergency_contact' => 'json',
        'bank_details' => 'json',
        'documents' => 'json',
        'salary' => 'decimal:2',
        'allowance_housing' => 'decimal:2',
        'allowance_medical' => 'decimal:2',
        'allowance_transport' => 'decimal:2',
        'deduction_tax' => 'decimal:2',
        'deduction_other' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function calculateNetSalary(): float
    {
        $base = (float) ($this->salary ?? 0);
        $allowances = (float) ($this->allowance_housing ?? 0) + (float) ($this->allowance_medical ?? 0) + (float) ($this->allowance_transport ?? 0);
        $deductions = (float) ($this->deduction_tax ?? 0) + (float) ($this->deduction_other ?? 0);

        return max(0, ($base + $allowances) - $deductions);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
