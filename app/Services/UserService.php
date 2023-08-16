<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Register user
     *
     * @param array $validated the validated new user data
     * @param boolean $emitRegisteredEvent
     * @return User
     */
    public function register(array $validated, bool $emitRegisteredEvent = true)
    {
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'verification_token' => Str::random(50),
            'password' => Hash::make($validated['password']),
        ]);

        // notify admins
        \Notification::send(
            (new User)->whereHasAdminAccess()->get(),
            new UserRegisteredNotification($user)
        );

        if ($emitRegisteredEvent)
            event(new UserRegistered($user));

        return $user;
    }

    /**
     * Update user
     *
     * @param User $user
     * @param array $validated
     * @return User
     */
    public function update(User $user, array $validated)
    {
        if ($validated['password'] ?? null)
            $validated['password'] = Hash::make($validated['password']);

        $user->update($validated);

        return $user;
    }

    /**
     * Set status deleted to user
     *
     * @param User $user
     * @return bool
     */
    public function remove(User $user)
    {
        if ($user->isSuperadmin() && User::where("level", User::LEVEL_SUPER)->count() === 1) {
            throw new UnauthorizedActionException("You are the last super administrator of the system");
        }

        $user = $this->photoDelete($user);

        return $user->update([
            "status" => "deleted"
        ]);
    }

    /**
     * Delete user
     *
     * @param User $user
     * @return boolean
     */
    public function delete(User $user)
    {
        $user = $this->photoDelete($user);
        return $user->delete();
    }

    /**
     * Delete user foto from disk and save user with null in photo field
     *
     * @param User $user
     * @return User
     */
    public function photoDelete(User $user)
    {
        if ($user->photo) {
            Storage::delete("" . $user->photo);

            $user->photo = null;
            $user->save();
        }

        return $user;
    }

    /**
     * Remove status deleted to user
     *
     * @param User $user
     * @return bool
     */
    public function recovery(User $user)
    {
        return $user->update([
            "status" => null
        ]);
    }
}