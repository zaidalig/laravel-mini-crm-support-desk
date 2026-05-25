<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Staff extends Model
{
    use LogsActivity;

    // Set custom table name since standard pluralization for Staff is staff, which matches migrations.
    protected $table = 'staff';

    protected $fillable = [
        'name',
        'email',
        'role',
        'status',
    ];

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
}
