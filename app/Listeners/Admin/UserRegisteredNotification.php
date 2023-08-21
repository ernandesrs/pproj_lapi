<?php

namespace App\Listeners\Admin;

use App\Events\UserRegistered;
use App\Models\User;
use App\Notifications\UserRegisteredNotification as NewUserRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegisteredNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        \Notification::send(
            (new User)->whereHasAdminAccess()->get(),
            new NewUserRegisteredNotification($event->user)
        );
    }
}