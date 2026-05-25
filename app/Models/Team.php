<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'team_lead_id',
        'status',
    ];

    public function lead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('member_role')->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
}
