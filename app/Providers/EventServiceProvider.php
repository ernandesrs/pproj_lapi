<?php

namespace App\Providers;

use App\Events\EmailUpdateRequested;
use App\Events\ForgetPassword;
use App\Events\UserRegistered;
use App\Listeners\Admin\UserRegisteredNotify;
use App\Listeners\SendEmailUpdateVerificationLink;
use App\Listeners\SendResetPasswordLink;
use App\Listeners\SendVerificationLink;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
            // Registered::class => [
            //     SendEmailVerificationNotification::class,
            // ],
        UserRegistered::class => [
            SendVerificationLink::class,
            UserRegisteredNotify::class
        ],
        ForgetPassword::class => [
            SendResetPasswordLink::class
        ],
        EmailUpdateRequested::class => [
            SendEmailUpdateVerificationLink::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}