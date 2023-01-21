<?php

namespace App\Console\Defaults;

use App\Models\Role;

class StartRole
{
    /**
     * Create visitor role
     *
     * @return Role
     */
    public function visitor()
    {
        $permissibles = [];

        foreach (Role::PERMISSIBLES as $k => $v) {
            $k = str_replace('\\', '_', $k);
            $permissibles[$k] = $v;
        }

        return Role::create([
            'display_name' => 'Visitor',
            'permissibles' => $permissibles
        ]);
    }

    /**
     * Create visitor role
     *
     * @return Role
     */
    public function admin()
    {
        $permissibles = [];

        foreach (Role::PERMISSIBLES as $k => $v) {
            $k = str_replace('\\', '_', $k);

            $permissibles[$k] = $v;
            if (in_array($k, ['App_Models_User'])) {
                foreach ($v as $sk => $sv) {
                    $permissibles[$k][$sk] = true;
                }
            }
        }

        return Role::create([
            'display_name' => 'Admin',
            'permissibles' => $permissibles
        ]);
    }
}
