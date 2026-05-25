<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'designation',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
