<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'days_allowed', 'color', 'is_paid', 'is_active', 'description'];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
