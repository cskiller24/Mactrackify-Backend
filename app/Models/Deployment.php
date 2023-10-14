<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory, HasUuids;

    public const ACCEPTED = 'accepted';
    public const DECLINED = 'declined';
    public const NO_RESPONSE = 'no_response';


    protected $fillable = [
        'user_id',
        'team_id',
        'status',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeToday()
    {
        return $this->query()->where('date', now()->toDateString());
    }

    public function scopeTommorow()
    {
        return $this->query()->where('date', now()->addDay()->toDateString());
    }

    public static function createForUser($user_id, $team_id)
    {
        return self::query()->create([
            'user_id' => $user_id,
            'team_id' => $team_id,
            'status' => self::NO_RESPONSE,
        ]);
    }

    public function isAccepted(): bool
    {
        return $this->status === self::ACCEPTED;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::DECLINED;
    }

    public function isNoResponse(): bool
    {
        return $this->status === self::NO_RESPONSE;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deployment) {
            $deployment->date = now()->addDay()->toDateString();
        });
    }
}
