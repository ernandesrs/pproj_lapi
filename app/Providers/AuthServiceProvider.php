<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\Package' => 'App\Policies\PackagePolicy',
        'App\Models\Subscription' => 'App\Policies\SubscriptionPolicy',
        'App\Models\Admin\Setting' => 'App\Policies\Admin\SettingPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
