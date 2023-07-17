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
            'id' => $this->id,
            'app_name' => $this->app_name,
            'name' => $this->name,
        ];

        if (\Auth::user()->isSuperAdmin()) {
            $arr['data'] = $this->data;
            if (is_string($arr['data'])) {
                $arr['data'] = json_decode($arr['data']);
            }

            switch ($this->name) {
                case 'SettingAll':
                    if ($arr['data']?->smtp) {
                        $arr['data']->smtp->port = str_repeat("*", strlen($arr['data']->smtp->port));
                        $arr['data']->smtp->username = str_repeat("*", strlen($arr['data']->smtp->username));
                        $arr['data']->smtp->password = str_repeat("*", strlen($arr['data']->smtp->password));
                    }
                    break;
                case 'SettingDash':
                    if ($arr['data']?->gateways) {
                        $arr['data']->gateways->pagarme->test_api_key = str_repeat("*", strlen($arr['data']->gateways->pagarme->test_api_key));
                        $arr['data']->gateways->pagarme->live_api_key = str_repeat("*", strlen($arr['data']->gateways->pagarme->live_api_key));
                    }
                    break;
            }
        } else {
            $arr['data'] = [];
        }

        return $arr;
    }
}