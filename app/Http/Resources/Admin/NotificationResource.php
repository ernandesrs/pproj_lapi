<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => str_replace('\\', '_', $this->type),
            'read_at' => $this->read_at,
            'data' => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}