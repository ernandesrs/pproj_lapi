<?php

namespace App\Providers;

use App\Events\ForgetPassword;
use App\Events\UserRegistered;
use App\Listeners\Admin\UserRegisteredNotification as UserRegisteredNotificationListener;
use App\Listeners\SendResetPasswordLink;
use App\Listeners\SendUserVerificationLink;
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
            SendUserVerificationLink::class,
            UserRegisteredNotificationListener::class
        ],
        ForgetPassword::class => [
            SendResetPasswordLink::class
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
