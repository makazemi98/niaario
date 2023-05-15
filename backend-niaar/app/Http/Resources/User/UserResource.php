<?php

namespace App\Http\Resources\User;

use App\Enums\InquiryStatusesEnum;
use App\Enums\RolesEnum;
use App\Http\Resources\Comments\CommentResource;
use App\Interfaces\Repositories\InquiryRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];

        if (!is_null($this->resource)) {
            $data = [
                'id' => $this->id,
                'name' => $this->full_name,
                'role' => $this->getRoleNames()->first(),
                'abbreviation' => $this->abbreviation,
                'avatar' => $this->getFirstMediaUrl('avatar')?: null,
                'renewal_date' => $this->renewal_date,
                'confidential' => $this->whenLoaded('confidential', $this->confidential?->body),
                'contact_person' => $this->contact_person,
                'mobile_number' => $this->mobile_number,
                'company_name' => $this->company_name,
                'company_number' => $this->company_number,
                'contact_name_2' => $this->contact_name_2,
                'mobile_number_2' => $this->mobile_number_2,
                'company_abbreviation' => $this->company_abbreviation,
                $this->mergeWhen(
                    $request->routeIs('admin.team_members') ||
                    $request->routeIs('admin.pending-tasks') ||
                    $request->routeIs('users.index'),
                    function () {
                        $stats['all_inquiries_count'] = $this->resource->all_inquiries_count;
                        $this->resource->offsetUnset('all_inquiries_count');
                        foreach ($this->getAttributes() as $key => $value) {
                            if (in_array($key, InquiryStatusesEnum::values())) {
                                $stats[$key] = $value;
                                $this->resource->offsetUnset($key);
                            }
                        }

                        return ['stats' => $stats];
                    }
                ),
                $this->mergeWhen(
                    $request->routeIs('users.show') || $request->routeIs('users.update'),
                    function () {
                        return [
                            'first_name' => $this->first_name,
                            'last_name' => $this->last_name,
                            'email' => $this->email,
                            'gender' => $this->gender,
                        ];
                    }
                ),
                $this->mergeWhen(
                    $this->getRoleNames()->first() == RolesEnum::PROCUREMENT->value &&
                    !$request->routeIs('admin.pending-tasks'),
                    function () {
                        return [
                            'profit' => resolve(InquiryRepositoryInterface::class)
                                ->calcProfitByUser($this->id),
                            'average_response_time' => $this->average_response_time,
                            'success_ratio' => $this->success_ratio
                        ];
                    }
                ),
                $this->mergeWhen(
                    $this->getRoleNames()->first() == RolesEnum::CLIENT->value &&
                    !$request->routeIs('inquiries.future-dues.index'),
                    function () {
                        return [
                            'balance' => $this->balance,
                            'order_ratio' => $this->order_ratio,
                            'pending_quotations' => $this->pending_quotations
                        ];
                    }
                ),
            ];
        }

        return $data;
    }
}
