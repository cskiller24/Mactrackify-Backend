<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'balance'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
