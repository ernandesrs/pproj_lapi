<?php

namespace App\Http\Resources;

use App\Policies\RolePolicy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoleResource extends JsonResource
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
            "auth_user_can" => [
                "view" => (new RolePolicy())->view(Auth::user(), $this->resource),
                "update" => (new RolePolicy())->update(Auth::user(), $this->resource),
                "delete" => (new RolePolicy())->delete(Auth::user(), $this->resource),
            ]
        ]);
    }
}
