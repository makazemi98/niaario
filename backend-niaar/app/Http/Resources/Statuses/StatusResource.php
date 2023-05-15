<?php

namespace App\Http\Resources\Statuses;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'name' => strtoupper(str_replace('_', ' ', $this->name)),
            'reason' => $this->reason,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at
        ];;
    }
}
