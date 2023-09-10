<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\SendVerificationLinkJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerificationLink
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
        /**
         * 
         * Send verification link if user is not verified
         * 
         */
        if (!$event->user->email_verified_at) {
            SendVerificationLinkJob::dispatch($event->user);
        }
    }
}