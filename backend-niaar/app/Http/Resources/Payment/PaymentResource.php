<?php

namespace App\Http\Resources\Payment;

use App\Enums\PaymentTabsEnum;
use App\Http\Resources\Inquiry\InquiryResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'debit' => $this->debit,
            'credit' => $this->credit,
            'balance' => $this->balance,
            'date' => $this->date,
            'inquiry' => InquiryResource::make($this->whenLoaded('inquiry')),
            $this->mergeWhen(
                $request->segment(5) == PaymentTabsEnum::FUTURE_DUES->value,
                function () {
                    return [
                        'is_paid' => $this->is_paid,
                        'past_due' => $this->past_due
                    ];
                }
            ),
            $this->mergeWhen(
                $request->segment(5) == PaymentTabsEnum::PETTY->value,
                function () {
                    return [
                        'invoice_no' => $this->invoice_no
                    ];
                }
            ),
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'customer' => UserResource::make($this->whenLoaded('customer'))
        ];
    }
}
