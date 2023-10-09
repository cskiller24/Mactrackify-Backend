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

    public function brandAmbassador()
    {
        return $this->belongsTo(User::class);
    }
}
