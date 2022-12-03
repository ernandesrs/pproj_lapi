<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        dd($request->all());
    }

    public function forgetPassword(Request $request)
    {
        dd($request->all());
    }

    public function updatePassword(Request $request)
    {
        dd($request->all());
    }

    /**
     * Register an user
     *
     * @param AccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AccountRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'verification_token' => Str::random(50),
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            "success" => true,
            "user" => $user
        ]);
    }

    public function logout()
    {
    }

    public function verifyAccount()
    {
    }

    public function resendVerification()
    {
    }
}
