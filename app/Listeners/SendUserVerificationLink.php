<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\RegisteredUserVerificationLinkJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserVerificationLink
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
        RegisteredUserVerificationLinkJob::dispatch($event->user);
    }
}