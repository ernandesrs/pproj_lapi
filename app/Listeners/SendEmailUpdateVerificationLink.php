<?php

namespace App\Listeners;

use App\Events\EmailUpdateRequested;
use App\Jobs\SendEmailUpdateVerificationLinkJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailUpdateVerificationLink
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
     * @param  \App\Events\EmailUpdateRequested  $event
     * @return void
     */
    public function handle(EmailUpdateRequested $event)
    {
        SendEmailUpdateVerificationLinkJob::dispatch($event->emailUpdate);
    }
}