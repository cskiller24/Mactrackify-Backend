<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product_name' => $this->warehouseItem->name,
            'warehouse_name' => $this->warehouseItem->warehouse->name,
            'warehouse_quantity' => $this->warehouseItem->quantity,
            'warehouse_description' => $this->warehouseItem->description,
            'warehouse_price' => $this->warehouseItem->price
        ];
    }
}
