<?php

namespace App\Http\Resources;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Sale  */
class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_name' =>  $this->customer_name,
            'customer_contact' => $this->customer_contact,
            'age' => $this->age,
            'brand_ambassador_name' => $this->brandAmbassador->full_name,
            'product_name' => $this->product_name,
            'product_quantity' => $this->product_quantity,
            'promo' => $this->promo,
            'promo_quantity' => $this->promo_quantity,
            'date' => $this->created_at->toDateString(),
            'created_at_diff' => $this->created_at->diffForHumans(),
            'signature_url' => $this->signature_url
        ];
    }
}
