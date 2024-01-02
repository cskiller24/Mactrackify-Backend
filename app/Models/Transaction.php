<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use Searchable;

    protected array $searchableFields = [
        'uuid',
        'account.name',
        'status',
        'user.first_name',
        'user.middle_name',
        'user.last_name'
    ];

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
