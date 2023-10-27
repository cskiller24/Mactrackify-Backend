<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const ADMIN = 'admin';
    const TEAM_LEADER = 'team_leader';
    const HUMAN_RESOURCE = 'human_resource';
    const BRAND_AMBASSADOR = 'brand_ambassador';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'latestTrack',
        'fullName',
        'hasTrack'
    ];

        public static function rolesList(): array
    {
        return [
            self::ADMIN,
            self::TEAM_LEADER,
            self::HUMAN_RESOURCE,
            self::BRAND_AMBASSADOR,
        ];
    }

    /**
     * ======================
     * Attributes
     */

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ADMIN);
    }

    public function isTeamLeader(): bool
    {
        return $this->hasRole(self::TEAM_LEADER);
    }

    public function isHumanResource(): bool
    {
        return $this->hasRole(self::HUMAN_RESOURCE);
    }

    public function isBrandAmbassador(): bool
    {
        return $this->hasRole(self::BRAND_AMBASSADOR);
    }

    public function hasTeams(): bool
    {
        return $this->teams()->count() > 0;
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->last_name}, {$this->first_name} {$this->middle_name}"
        );
    }

    public function latestStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statuses()->latest()->first(),
        );
    }

    public function latestTrack(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tracks()->latest()->first()
        );
    }

    public function getLatestTrackAttribute()
    {
        return $this->tracks()->latest()->first();
    }

    public function getHasTrackAttribute()
    {
        return $this->tracks()->latest()->exists();
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name}, {$this->first_name} {$this->middle_name}";
    }

    public function latestDeployment()
    {
        return Attribute::make(
            get: fn () => $this->deployments()->latest()->first()
        );
    }

    /**
     * ======================
     * RELATIONSHIPS
     */

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'teams_users')
            ->withPivot('is_leader')
            ->withTimestamps();
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(Status::class);
    }

    public function sales()
    {
        if($this->brandAmbassador()) {
            return $this->hasMany(Sale::class, 'brand_ambassador_id');
        }

        if($this->isTeamLeader()) {
            return $this->hasMany(Sale::class, 'team_leader_id');
        }

        throw new Exception("User is neither brand ambassador or team leader when accessing sales");
    }

    public function tracks()
    {
        return $this->hasMany(Track::class, 'brand_ambassador_id')->latest();
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function latestTracking()
    {
        return $this->tracks()->latest();
    }

    /**
     * ======================
     * SCOPES
     */
    public function scopeWithoutTeam(Builder $query)
    {
        return $query->whereDoesntHave('teams');
    }

    public function scopeWithTeam(Builder $query)
    {
        return $query->has('teams');
    }

    public function scopeAdmin(Builder $query)
    {
        return $query->role(self::ADMIN);
    }

    public function scopeTeamLeader(Builder $query)
    {
        return $query->role(self::TEAM_LEADER);
    }

    public function scopeHumanResource(Builder $query)
    {
        return $query->role(self::HUMAN_RESOURCE);
    }

    public function scopeBrandAmbassador(Builder $query)
    {
        return $query->role(self::BRAND_AMBASSADOR);
    }
}
