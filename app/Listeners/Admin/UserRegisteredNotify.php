<?php

namespace App\Listeners\Admin;

use App\Events\UserRegistered;
use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegisteredNotify
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
            new UserRegisteredNotification($event->user)
        );
    }
}