<?php

namespace App\Console\Defaults;

class StartApp
{
    /**
     * Contructor
     *
     * @param string $mail the super user mail
     * @param string $pass the super user password
     */
    public function __construct(string $mail, string $pass)
    {
        $visitorRole = (new StartRole())->visitor();
        $adminRole = (new StartRole())->admin();

        $super = (new StartUser())->super($mail, $pass);
        $admin = (new StartUser())->admin($adminRole);
        $visitor = (new StartUser())->visitor($visitorRole);
    }
}
