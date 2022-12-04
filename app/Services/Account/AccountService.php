<?php

namespace App\Services\Account;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService
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
}
