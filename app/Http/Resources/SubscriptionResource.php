<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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

        if (in_array('admin', $request->route()->middleware())) {
            $adminArr = [
                'user' => $this->resource->user()->first()
            ];
        }

        return array_merge(parent::toArray($request), $adminArr);
    }
}