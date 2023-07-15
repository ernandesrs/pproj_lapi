<?php

namespace App\Models\Admin;

use App\Models\Admin\Setting;

/**
 * SettingAll: model for system-wide related settings(admin, dash, ...)
 * This model has the following configurations:
 * * name
 * * app_name
 * * data
 * * * smtp
 * * * * host
 * * * * port
 * * * * username
 * * * * password
 * * * * encryption
 * * * * from
 */
class SettingAll extends Setting
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
    public const NAME = 'SettingAll';

    /**
     * Create
     *
     * @param array $attributes
     * @return SettingAll
     */
    public static function create(array $attributes = [])
    {
        $attributes['app_name'] = env('APP_NAME');
        $attributes['name'] = self::NAME;
        $attributes['data'] = json_encode([
            'smtp' => [
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PORT'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'encryption' => env('MAIL_ENCRYPTION'),
                'from' => env('MAIL_FROM_ADDRESS')
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
            'smtp' => [
                'host' => key_exists('host', $attributes['smtp']) ? $attributes['smtp']['host'] ?? null : $this->data->smtp->host,
                'port' => key_exists('port', $attributes['smtp']) ? $attributes['smtp']['port'] ?? null : $this->data->smtp->port,
                'username' => key_exists('username', $attributes['smtp']) ? $attributes['smtp']['username'] ?? null : $this->data->smtp->username,
                'password' => key_exists('password', $attributes['smtp']) ? $attributes['smtp']['password'] ?? null : $this->data->smtp->password,
                'encryption' => key_exists('encryption', $attributes['smtp']) ? $attributes['smtp']['encryption'] ?? null : $this->data->smtp->encryption,
                'from' => key_exists('from', $attributes['smtp']) ? $attributes['smtp']['from'] ?? null : $this->data->smtp->from,
            ]
        ]);

        unset($attributes['name']);
        unset($attributes['smtp']);

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
            'smtp' => ['nullable', 'array'],
            'smtp.host' => ['required_unless:smtp,null', 'string'],
            'smtp.port' => ['required_unless:smtp,null', 'numeric'],
            'smtp.username' => ['required_unless:smtp,null', 'string'],
            'smtp.password' => ['required_unless:smtp,null', 'string'],
            'smtp.encryption' => ['required_unless:smtp,null', 'string'],
            'smtp.from' => ['required_unless:smtp,null', 'string', 'email'],
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
            'smtp.required_array_keys' => 'Campos obrigatórios não preenchidos(host,port,username,password,encryption,from',
            'smtp.host.string' => 'Campo precisa ser url válida',
            'smtp.host.url' => 'Campo precisa ser url válida',
            'smtp.port.numeric' => 'Campo precisa ser numérico',
            'smtp.username' => 'Campo precisa ser preenchido',
            'smtp.password' => 'Campo precisa ser preenchido',
            'smtp.encryption' => 'Campo precisa ser preenchido',
            'smtp.from' => 'Campo precisa ser email válido',
            'smtp.from.string' => 'Campo precisa ser email válido',
            'smtp.from.email' => 'Campo precisa ser email válido',
        ];
    }
}