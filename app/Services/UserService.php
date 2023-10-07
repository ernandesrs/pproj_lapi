<?php

namespace App\Services;

use App\Events\EmailUpdateRequested;
use App\Events\UserRegistered;
use App\Exceptions\Account\EmailUpdateTokenInvalidException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Models\User;
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
            'password' => $validated['password'] ?? null ? Hash::make($validated['password']) : null,
        ]);

        if ($emitRegisteredEvent)
            event(new UserRegistered($user));

        return $user;
    }

    /**
     * Update user
     *
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $validated
     * @return User
     */
    public function update(User|\Illuminate\Contracts\Auth\Authenticatable $user, array $validated)
    {
        if ($validated['password'] ?? null)
            $validated['password'] = Hash::make($validated['password']);

        $user->update($validated);

        return $user;
    }

    /**
     * Update the user password
     *
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $validated
     * @return User
     */
    public function updatePassword(User|\Illuminate\Contracts\Auth\Authenticatable $user, array $validated)
    {
        $validated['password'] = Hash::make($validated['password']);

        $user->update($validated);

        return $user;
    }

    /**
     * Update user email
     *
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $validated
     * @return bool
     */
    public function requestEmailUpdate(User|\Illuminate\Contracts\Auth\Authenticatable $user, array $validated)
    {
        $emailUpdate = $user->emailUpdate()->first();
        if ($emailUpdate) {
            $emailUpdate->delete();
        }

        $emailUpdate = $user->emailUpdate()->create([
            "new_email" => $validated["new_email"],
            "token" => Str::random(49)
        ]);

        event(new EmailUpdateRequested($emailUpdate));

        return true;
    }

    /**
     * User email update
     *
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return bool
     * @throws EmailUpdateTokenInvalidException
     */
    public function emailUpdate(User|\Illuminate\Contracts\Auth\Authenticatable $user, string $token)
    {
        $emailUpdate = $user->emailUpdate()->where("token", $token)->first();
        if (!$emailUpdate) {
            throw new EmailUpdateTokenInvalidException;
        }

        $user->email = $emailUpdate->new_email;
        $user->save();

        $emailUpdate->delete();

        return true;
    }

    /**
     * Set status deleted to user
     *
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @return bool
     */
    public function remove(User|\Illuminate\Contracts\Auth\Authenticatable $user)
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
     * @param User|\Illuminate\Contracts\Auth\Authenticatable $user
     * @return User
     */
    public function photoDelete(User|\Illuminate\Contracts\Auth\Authenticatable $user)
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