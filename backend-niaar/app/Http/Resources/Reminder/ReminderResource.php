<?php

namespace App\Http\Resources\Reminder;

use App\Http\Resources\Comments\CommentResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'inquiry_id' => $this->inquiry_id,
            'reminder_date' => $this->reminder_date,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'comment' => new CommentResource($this->whenLoaded('comment'))
        ];
    }
}
