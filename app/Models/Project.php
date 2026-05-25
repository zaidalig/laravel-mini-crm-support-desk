<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use Carbon\Carbon;

class Project extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'client_id',
        'title',
        'description',
        'budget',
        'start_date',
        'deadline',
        'progress',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'budget' => 'decimal:2',
        'progress' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function isOverdue()
    {
        if (!$this->deadline) {
            return false;
        }
        return $this->deadline->isPast() && $this->status !== 'completed';
    }
}
