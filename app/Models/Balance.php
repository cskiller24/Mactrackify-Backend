<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance',
        'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
