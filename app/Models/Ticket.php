<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Ticket extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'client_id',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function isOverdue()
    {
        if (!$this->due_date) {
            return false;
        }
        return $this->due_date->isPast() && !in_array($this->status, ['resolved', 'closed']);
    }
}
