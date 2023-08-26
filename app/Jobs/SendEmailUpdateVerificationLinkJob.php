<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Models\UserEmailUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailUpdateVerificationLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\UserEmailUpdate
     */
    public $emailUpdate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserEmailUpdate $emailUpdate)
    {
        $this->emailUpdate = $emailUpdate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emailUpdate->user()->first()->email)->queue(new \App\Mail\UserEmailUpdate($this->emailUpdate));
    }
}