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
        'emergency_contact', 'bank_details', 'documents'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'emergency_contact' => 'json',
        'bank_details' => 'json',
        'documents' => 'json',
        'salary' => 'decimal:2',
    ];

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
