<?php

namespace App\Models\Admin;

use App\Models\Admin\Setting;

/**
 * SettingDash: model for system-wide related settings for dash panel
 * This model has the following configurations:
 * * name
 * * app_name
 * * data
 * * * gateways
 * * * * pagarme
 * * * * * test_api_key
 * * * * * live_api_key
 */
class SettingDash extends Setting
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Setting Name
     */
    public const NAME = 'SettingDash';

    /**
     * Create
     *
     * @param array $attributes
     * @return SettingDash
     */
    public static function create(array $attributes = [])
    {
        $attributes['app_name'] = 'DASH';
        $attributes['name'] = self::NAME;
        $attributes['data'] = json_encode([
            'gateways' => [
                'pagarme' => [
                    'test_api_key' => env('GATEWAY_PAGARME_API_TEST'),
                    'live_api_key' => env('GATEWAY_PAGARME_API_LIVE')
                ]
            ]
        ]);
        return Setting::create($attributes);
    }

    /**
     * Update
     *
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $attributes['data'] = json_encode([
            'gateways' => [
                'pagarme' => [
                    'test_api_key' => key_exists('test_api_key', $attributes['gateways']['pagarme']) ? $attributes['gateways']['pagarme']['test_api_key'] ?? null : $this->data?->gateways?->pagarme?->test_api_key,
                    'live_api_key' => key_exists('live_api_key', $attributes['gateways']['pagarme']) ? $attributes['gateways']['pagarme']['live_api_key'] ?? null : $this->data?->gateways?->pagarme?->live_api_key
                ]
            ]
        ]);

        unset($attributes["gateways"]);

        return parent::update($attributes, $options);
    }

    /**
     * Specific rules for data field
     *
     * @return array
     */
    public function rules()
    {
        return [
            'gateways' => ['nullable', 'array'],
            'gateways.pagarme' => ['required_unless:gateways,null', 'array'],
            'gateways.pagarme.test_api_key' => ['nullable', 'string'],
            'gateways.pagarme.live_api_key' => ['nullable', 'string']
        ];
    }

    /**
     * Rules messages
     *
     * @return array
     */
    public function rulesMessages()
    {
        return [
        ];
    }
}