<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    use HasFactory, HasUuids, Searchable;

    public const ACCEPTED = 'accepted';
    public const DECLINED = 'declined';
    public const NO_RESPONSE = 'no_response';

    protected $searchableFields = [
        'user.first_name',
        'user.middle_name',
        'user.last_name',
        'user.email',
        'team.name'
    ];

    protected $fillable = [
        'user_id',
        'team_id',
        'status',
        'date',
        'replaced'
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

    public static function createForUser($user_id, $team_id, $date)
    {
        return self::query()->create([
            'user_id' => $user_id,
            'team_id' => $team_id,
            'status' => self::NO_RESPONSE,
            'date' => $date
        ]);
    }

    public function isNotReplaced()
    {
        return !$this->replaced;
    }

    public function isReplaced()
    {
        return $this->replaced;
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
}
