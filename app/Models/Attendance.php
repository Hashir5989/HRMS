<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'clock_in', 'clock_out',
        'break_start', 'break_end', 'break_reason', 'break_minutes', 'on_break',
        'total_hours', 'status', 'notes', 'ip_address',
    ];

    protected $casts = [
        'date' => 'date',
        'total_hours' => 'decimal:2',
        'on_break' => 'boolean',
        'break_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
