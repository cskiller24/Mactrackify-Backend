<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_ambassador_id',
        'latitude',
        'longitude',
        'location',
        'is_authentic',
    ];

    protected $appends = [
        'createdAtDiff',
        'createdAtFormatted'
    ];

    public function brandAmbassador()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtDiffAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getCreatedAtFormattedAttribute() {
        return $this->created_at->toDateTimeString();
    }
}
