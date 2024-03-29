<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
        $adminArr = [];

        $adminArr['roles'] = RoleResource::collection($this->resource->roles()->get());
        $adminArr['addresses'] = AddressResource::collection($this->resource->addresses()->get());

        $arr = parent::toArray($request);
        $arr["photo_url"] = $this->when($this->resource->photo, Storage::url($this->resource->photo));

        return array_merge($arr, $adminArr);
    }
}