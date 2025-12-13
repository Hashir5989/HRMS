<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'project_code', 'description', 'client',
        'start_date', 'end_date', 'project_manager_id', 'team_id',
        'priority', 'status', 'progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Calculate progress dynamically from completed tasks if tasks exist,
     * otherwise fall back to manual progress stored in the database.
     */
    public function getProgressAttribute(): int
    {
        if (array_key_exists('tasks_count', $this->attributes)) {
            $totalTasks = (int) $this->attributes['tasks_count'];
            $completedTasks = (int) ($this->attributes['completed_tasks_count'] ?? 0);
        } else {
            $totalTasks = $this->tasks()->count();
            $completedTasks = $totalTasks > 0
                ? $this->tasks()->where('status', 'Completed')->count()
                : 0;
        }

        if ($totalTasks === 0) {
            return (int) ($this->attributes['progress'] ?? 0);
        }

        return (int) round(($completedTasks / $totalTasks) * 100);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
