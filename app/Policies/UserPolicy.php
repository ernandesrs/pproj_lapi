<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

        return $user->hasPermission("viewAny", User::class);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        if ($user->isSuperadmin())
            return true;

        return $user->hasPermission("view", User::class);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, User $model)
    {
        // superadmin have all permissions
        if ($user->isSuperadmin())
            return true;

        // permission by level
        if ($user->level <= $model->level)
            return false;

        // specified permissions
        return $user->hasPermission("create", User::class);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // superadmin have all permissions
        if ($user->isSuperadmin())
            return true;

        // permission by level
        if ($user->level <= $model->level)
            return false;

        // specified permissions
        return $user->hasPermission("update", User::class);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        if ($user->id === $model->id)
            return false;

        // superadmin have all permissions
        if ($user->isSuperadmin())
            return true;

        // permission by level
        if ($user->level <= $model->level)
            return false;

        // specified permissions
        return $user->hasPermission("delete", User::class);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        if ($user->isSuperadmin())
            return true;

        // permission by level
        if ($user->level <= $model->level)
            return false;

        return $user->hasPermission("restore", User::class);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        if ($user->isSuperadmin())
            return true;

        // permission by level
        if ($user->level <= $model->level)
            return false;

        return $user->hasPermission("forceDelete", User::class);
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function updateLevel(User $user, User $model)
    {
        return $user->isSuperadmin();
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function updateRole(User $user, User $model)
    {
        return $user->isSuperadmin() && $user->id !== $model->id;
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function deleteRole(User $user, User $model)
    {
        return $user->isSuperadmin();
    }
}