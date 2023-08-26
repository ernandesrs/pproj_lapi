<?php

namespace App\Console;

use App\Services\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        /**
         * Delete unverified users
         */
        if (is_numeric(env('DAYS_TO_DELETE_UNVERIFIED_USER'))) {
            /**
             * Finds and deletes unverified users who missed set verification timeout
             */
            $schedule->call(function () {
                \App\Models\User::whereNull("email_verified_at")
                    ->where("created_at", "<", Carbon::now()->subDays(env('DAYS_TO_DELETE_UNVERIFIED_USER')))
                    ->get()
                    ->each(function ($user) {
                        (new UserService())->delete($user);
                    });
            })->daily();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}