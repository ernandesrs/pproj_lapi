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
        $arr = parent::toArray($request);

        return array_merge($arr, [
            "photo_url" => $this->when($this->resource->photo, Storage::url($this->resource->photo)),
            "auth_user_can" => [
                "view" => (new UserPolicy)->view(Auth::user(), $this->resource),
                "update" => (new UserPolicy)->update(Auth::user(), $this->resource),
                "delete" => (new UserPolicy)->delete(Auth::user(), $this->resource),
            ]
        ]);
    }
}
