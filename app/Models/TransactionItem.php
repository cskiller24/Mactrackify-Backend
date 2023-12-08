<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'warehouse_item_id',
        'quantity'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function warehouseItem()
    {
        return $this->belongsTo(WarehouseItem::class, 'warehouse_item_id', 'id');
    }
}
