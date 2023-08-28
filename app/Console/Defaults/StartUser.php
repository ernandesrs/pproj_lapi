<?php

namespace App\Console\Defaults;

use Illuminate\Support\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;

class StartUser
{
    /**
     * Super
     *
     * @param string $mail
     * @param string $pass
     * @return user
     */
    public function super(string $mail, string $pass)
    {
        $user = (new UserService())->register([
            'first_name' => 'Super',
            'last_name' => 'User',
            'username' => 'superuser',
            'email' => $mail,
            'gender' => 'n',
            'password' => $pass
        ], false);
        $user->level = User::LEVEL_SUPER;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return $user;
    }

    /**
     * Admin
     *
     * @param Role $role
     * @return User
     */
    public function admin(Role $role)
    {
        $user = (new UserService())->register([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'adminuser',
            'email' => 'admin@mail.com',
            'gender' => 'n',
            'password' => 'admin'
        ], false);

        $user->level = User::LEVEL_ADMIN;
        $user->email_verified_at = Carbon::now();
        $user->save();
        $user->roles()->attach($role->id);

        return $user;
    }

    /**
     * Visitor
     *
     * @param Role $role
     * @return User
     */
    public function visitor(Role $role)
    {
        $user = (new UserService())->register([
            'first_name' => 'Visitor',
            'last_name' => 'User',
            'username' => 'visitoruser',
            'email' => 'visitor@mail.com',
            'gender' => 'n',
            'password' => 'visitor'
        ], false);

        $user->level = User::LEVEL_ADMIN;
        $user->email_verified_at = Carbon::now();
        $user->save();
        $user->roles()->attach($role->id);

        return $user;
    }
}