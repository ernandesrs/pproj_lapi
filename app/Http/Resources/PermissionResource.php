<?php

namespace App\Http\Resources;

use App\Policies\PermissionPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = parent::toArray($request);
        return array_merge($arr, [
            "users_count" => $this->resource->users()->count(),
            "auth_user_can" => [
                "view" => (new PermissionPolicy)->view(Auth::user(), $this->resource),
                "update" => (new PermissionPolicy)->update(Auth::user(), $this->resource),
                "delete" => (new PermissionPolicy)->delete(Auth::user(), $this->resource),
            ]
        ]);
    }
}
