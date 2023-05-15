<?php

namespace App\Http\Resources\Inquiry;

use App\Enums\PaymentTabsEnum;
use App\Http\Resources\Calculation\CalculationCollection;
use App\Http\Resources\Comments\CommentCollection;
use App\Http\Resources\FutureDue\FutureDueCollection;
use App\Http\Resources\Media\MdiaCollection;
use App\Http\Resources\Statuses\StatusCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
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
            'client' => new UserResource($this->whenLoaded('client')),
            'title' => $this->title,
            $this->mergeWhen(
                $request->routeIs('inquiries.show') ||
                $request->routeIs('inquiries.log'),
                function () {
                    return [
                        'description' => $this->description,
                        'remark' => $this->remarks,
                    ];
                }
            ),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'comments' => new CommentCollection($this->whenLoaded('comments')),
            'statuses' => new StatusCollection($this->whenLoaded('statuses')),
            'calculations' => new CalculationCollection($this->whenLoaded('calculations')),
            'future_dues' => new FutureDueCollection($this->whenLoaded('futureDues')),
            'status' => $this->status,
            'media' => new MdiaCollection($this->whenLoaded('media')),
            'created_at' => $this->created_at,
            'update_at' => $this->updated_at,

            $this->merge(
                function () {
                    $this->resource->load([
                        'calculations' => function ($query) {
                            $query->where('is_extra', 0);
                        }
                    ]);

                    $totalQuoted = $this->getTotal('quoted_price');

                    $totalPurchasing = $this->getTotal('buying_price');

                    $totalActualOrdered = $this->getTotal('actual_ordered_price');

                    return [
                        'meta' => [
                            'profit' => [
                                'assumed_profit' => $totalQuoted - $totalPurchasing,
                                'actual_profit' => $totalActualOrdered - $totalPurchasing
                            ],
                            'total' => 0,
                            'total_buying_aed' => $this->getTotal('buying_total_price_aed'),
                            'total_quoted_aed' => $this->getTotal('quoted_price_aed'),
                            'total_actual_ordered_aed' => $this->getTotal('actual_ordered_price_aed'),
                        ]
                    ];
                }
            )
        ];
    }

    /**
     * Calculate inquiry meta field
     *
     * @param $field
     * @return float|int
     */
    protected function getTotal($field)
    {
        return $this->resource->calculations->where('deleted_at', null)->sum($field) * ($this->qty);
    }
}
