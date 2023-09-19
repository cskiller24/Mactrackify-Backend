<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Storage;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_name',
        'team_leader_name',
        'brand_ambassador_name',
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
}
