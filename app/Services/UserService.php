<?php

namespace App\Services;

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
     * @return User
     */
    public function register(array $validated)
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

        return $user;
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
            Storage::delete("public/" . $user->photo);

            $user->photo = null;
            $user->save();
        }

        return $user;
    }
}
