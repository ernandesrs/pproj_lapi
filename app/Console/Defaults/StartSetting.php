<?php

namespace App\Console\Defaults;

use App\Models\Admin\SettingAdmin;
use App\Models\Admin\SettingAll;
use App\Models\Admin\SettingDash;

class StartSetting
{
    /**
     * Contructor
     */
    public function __construct()
    {
        SettingAll::create();
        SettingAdmin::create();
        SettingDash::create();
    }
}