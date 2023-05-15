<?php

namespace App\Http\Resources\Shipping;

use App\Http\Resources\Box\BoxCollection;
use App\Http\Resources\Comments\CommentCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingResource extends JsonResource
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
            'captain_info' => $this->captain_info,
            'agent_name' => $this->agent_name,
            'agent_no' => $this->agent_no,
            'handed_over_date' => $this->handed_over_date,
            'sign' => $this->sign,
            'status' => strtolower($this->status),
            'boxes_count' => $this->boxes_count,
            'comments' => new CommentCollection($this->whenLoaded('comments')),
            'boxes' => new BoxCollection($this->whenLoaded('boxes'))
        ];
    }
}
