<?php

namespace App\Models\Admin;

use App\Models\Admin\Setting;

/**
 * SettingAdmin: model for system-wide related settings for admin panel
 * This model has the following configurations:
 * * name
 * * app_name
 */
class SettingAdmin extends Setting
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
    public const NAME = 'SettingAdmin';

    /**
     * Create
     *
     * @param array $attributes
     * @return SettingAdmin
     */
    public static function create(array $attributes = [])
    {
        $attributes['app_name'] = 'ADMIN';
        $attributes['name'] = self::NAME;
        $attributes['data'] = json_encode([
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
        ]);

        unset($attributes['name']);

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