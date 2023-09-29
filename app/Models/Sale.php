<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Storage;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'team_leader_id',
        'brand_ambassador_id',
        'customer_name',
        'customer_contact',
        'product',
        'product_quantity',
        'promo',
        'promo_quantity',
        'signature'
    ];

    public function signatureUrl(): Attribute
    {
        return Attribute::make(function () {
            return Crypt::encryptString(Storage::disk('customer_images')->url('').$this->signature);
        });
    }

    public function brandAmbassador()
    {
        return $this->belongsTo(User::class, 'brand_ambassador_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeTeamLeaderWide(Builder $query)
    {
        if(! auth()->user()->isTeamLeader()) {
            throw new Exception("You are not a team leader to access scopeTeamLeader method");
        }

        return $query->whereTeamLeaderId(auth()->id());
    }

    public function scopeBrandAmbassadorWide(Builder $query)
    {
        if(! auth()->user()->isBrandAmbassador()) {
            throw new Exception("You are not a brand ambassador to access scopeBrandAmbassador method");
        }

        return $query->whereBrandAmbassadorId(auth()->id());
    }
}
