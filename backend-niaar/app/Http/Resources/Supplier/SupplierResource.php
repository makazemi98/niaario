<?php

namespace App\Http\Resources\Supplier;

use App\Http\Resources\Brand\BrandCollection;
use App\Http\Resources\ProductCategory\ProductCategoryCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'website' => $this->website,
            'persons_in_charge' => new UserCollection($this->whenLoaded('personsInCharge')),
            'country' => $this->country,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'customers' => new UserCollection($this->whenLoaded('customers')),
            'supplying_brands' => new BrandCollection($this->whenLoaded('supplyingBrands')),
            'product_categories' => new ProductCategoryCollection($this->whenLoaded('productCategories'))
        ];
    }
}
