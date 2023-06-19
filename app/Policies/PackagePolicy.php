<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("viewAny", Package::class);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Package $package)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("view", Package::class);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("create", Package::class);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Package $package)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("update", Package::class);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Package $package)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("delete", Package::class);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Package $package)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("restore", Package::class);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Package $package)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("forceDelete", Package::class);
    }
}