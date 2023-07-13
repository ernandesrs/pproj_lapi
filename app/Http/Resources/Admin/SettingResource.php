<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = [
            'app_name' => $this->app_name,
            'name' => $this->name,
        ];

        if (\Auth::user()->isSuperAdmin()) {
            $arr['data'] = $this->data;
        } else {
            $arr['data'] = [];
        }

        return $arr;
    }
}