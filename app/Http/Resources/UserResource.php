<?php

namespace App\Http\Resources;

use App\Policies\UserPolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
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

        if (in_array('admin', $request->route()->middleware())) {
            $adminArr['auth_user_can'] = [
                "view" => (new UserPolicy)->view(\Auth::user(), $this->resource),
                "update" => (new UserPolicy)->update(\Auth::user(), $this->resource),
                "delete" => (new UserPolicy)->delete(\Auth::user(), $this->resource),
            ];

            $adminArr['roles'] = $this->resource->roles()->get();
        }

        $arr = parent::toArray($request);
        $arr["photo_url"] = $this->when($this->resource->photo, Storage::url($this->resource->photo));

        return array_merge($arr, $adminArr);
    }
}