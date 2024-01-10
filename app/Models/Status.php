<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    use HasFactory;

    const AVAILABLE = 'available';
    const NOT_AVAILABLE = 'not_available';
    const PENDING = 'pending';

    protected $fillable = [
        'status',
        'user_id'
    ];

    public static function listStatuses()
    {
        return [
            self::AVAILABLE,
            self::NOT_AVAILABLE,
            self::PENDING
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === self::AVAILABLE;
    }

    public function isNotAvailable(): bool
    {
        return $this->status === self::NOT_AVAILABLE;
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }
}
