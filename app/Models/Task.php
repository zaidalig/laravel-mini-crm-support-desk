<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Task extends Model
{
    use LogsActivity;

    protected $fillable = [
        'project_id',
        'ticket_id',
        'title',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    public function isOverdue()
    {
        if (!$this->due_date) {
            return false;
        }
        return $this->due_date->isPast() && $this->status !== 'completed';
    }
}
