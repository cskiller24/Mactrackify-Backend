<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public static function rolesList(): array
    {
        return [
            self::ADMIN,
            self::TEAM_LEADER,
            self::HUMAN_RESOURCE,
            self::BRAND_AMBASSADOR,
        ];
    }

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
}
