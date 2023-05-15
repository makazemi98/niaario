<?php

namespace App\Http\Resources\Calculation;

use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Supplier\SupplierResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'supplier_id' => new SupplierResource($this->whenLoaded('supplier')),
            'buying_price' => $this->buying_price,
            'buying_currency' => $this->buying_currency,
            'buying_total_price_aed' => $this->buying_total_price_aed,
            'quoted_price' => $this->quoted_price,
            'quoted_currency' => $this->quoted_currency,
            'quoted_price_aed' => $this->quoted_price_aed,
            'actual_ordered_price_aed' => $this->actual_ordered_price_aed,
            'qty' => $this->qty,
            'is_extra' => $this->is_extra,
            'remark' => new CommentResource($this->whenLoaded('remark')),
            'description' => new CommentResource($this->whenLoaded('description')),
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
