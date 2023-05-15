<?php

namespace App\Http\Resources\Payment;

use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\Inquiry\InquiryResource;
use App\Http\Resources\Supplier\SupplierResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceSheetResource extends JsonResource
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
            'description' => $this->description,
            'paid' => $this->debit,
            'received' => $this->credit,
            'balance' => $this->balance,
            'date' => $this->date,
            'category' => $this->category,
            'order_no' => $this->invoice_no,
            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),
            'remark' => CommentResource::make($this->whenLoaded('remark')),
            'inquiry' => InquiryResource::make($this->whenLoaded('inquiry')),
            'creator' => UserResource::make($this->whenLoaded('creator'))
        ];
    }
}
