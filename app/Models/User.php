<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'team_lead_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('member_role')->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, ['owner', 'manager'], true);
    }

    public function canManageTeams(): bool
    {
        return in_array($this->role, ['owner', 'manager'], true);
    }

    public function canManageCrm(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'support'], true);
    }
}
