<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invite extends Model
{
    use HasFactory, Searchable;

    protected array $searchableFields = ['*'];

    protected $fillable = [
        'email',
        'code',
        'role'
    ];

    public function formattedRole(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::title(str_replace('_', ' ', $this->role))
        );
    }

    public function acceptLink(): Attribute
    {
        return Attribute::make(
            get: fn() => route('invites.accept', $this->code)
        );
    }

    public static function boot() {
        parent::boot();

        static::creating(function (self $model) {
            $model->code = Str::ulid();
        });
    }
}
