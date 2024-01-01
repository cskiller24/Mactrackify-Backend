<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    use Searchable;

    protected array $searchable = ['*'];

    protected $fillable = [
        'address',
        'name'
    ];

    public function items()
    {
        return $this->hasMany(WarehouseItem::class);
    }
}
