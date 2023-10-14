<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function teamLeaderIds(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->leaders->map(fn ($leader) => $leader->id)
        );
    }

    public function membersId(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->members->map(fn ($leader) => $leader->id)
        );
    }

    public function leaders()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->wherePivot('is_leader', true);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->wherePivot('is_leader', false);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'teams_users')->withPivot('is_leader');
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function todayDeployments()
    {
        return $this->deployments()->where('date', now()->toDateString());
    }

    public function tommorowDeployments()
    {
        return $this->deployments()->where('date', now()->addDay()->toDateString());
    }

    public function hasDeploymentTommorow(): bool
    {
        return $this->deployments()->latest()->first() === now()->addDay()->toDateString();
    }

    public function scopeTeamLeaderWide(Builder $query)
    {
        return $query->whereHas('leaders', function ($q) {
            $q->where('users.id', auth()->id());
        });
    }
}
