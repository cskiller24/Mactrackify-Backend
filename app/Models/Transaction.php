<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'account_id',
        'status',
        'user_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function collectionHistories()
    {
        return $this->hasMany(CollectionHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
