<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
                'subscriptions' => [
                    'total' => $this->resource->subscriptions()->count(),
                    'active' => $this->resource->subscriptions()->where('status', Subscription::STATUS_ACTIVE)->count(),
                    'pending' => $this->resource->subscriptions()->where('status', Subscription::STATUS_PENDING)->count(),
                    'canceled' => $this->resource->subscriptions()->where('status', Subscription::STATUS_CANCELED)->count(),
                    'ended' => $this->resource->subscriptions()->where('status', Subscription::STATUS_ENDED)->count(),
                ]
            ];
        }

        return array_merge(parent::toArray($request), $adminArr);
    }
}