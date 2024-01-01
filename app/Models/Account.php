<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    use Searchable;

    protected array $searchable = ['*'];

    protected $fillable = [
        'name',
        'number',
        'address'
    ];

    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
