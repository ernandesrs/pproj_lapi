<?php

namespace App\Http\Controllers\Auth;

use App\Events\ForgetPassword;
use App\Events\UserRegistered;
use App\Exceptions\Account\LoginFailException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ForgetRequest;
use App\Http\Requests\Account\LoginRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Request\AccountRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accountService = new AccountService();
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $token = Auth::attempt([
            "email" => $validated["email"],
            "password" => $validated["password"]
        ]);

        if (!$token) {
            throw new LoginFailException();
        }

        return response()->json([
            "success" => true,
            "user" => Auth::user(),
            "access" => [
                "token" => $token,
                "type" => "Bearer",
                "full" => "Bearer " . $token,
                "expire_in_minutes" => config("jwt.ttl")
            ]
        ]);
    }

    /**
     * Foget password
     *
     * @param ForgetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(ForgetRequest $request)
    {
        $validated = $request->validated();
        $user = User::where("email", $validated["email"])->firstOrFail();

        $token = Str::random(60);
        PasswordReset::create([
            "email" => $user->email,
            "token" => $token
        ]);

        event(new ForgetPassword($user, $token));

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Update password
     *
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $resetPassword = PasswordReset::where("token", $validated["token"])->firstOrFail();
        $user = User::where("email", $resetPassword->email)->firstOrFail();
        $user->update([
            "password" => Hash::make($validated["password"])
        ]);

        PasswordReset::where("email", $user->email)->delete();

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Register an user
     *
     * @param AccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AccountRequest $request)
    {
        $user = $this->accountService->register(
            $request->validated()
        );

        event(new UserRegistered($user));

        return response()->json([
            "success" => true,
            "user" => $user
        ]);
    }

    /**
     * Logout
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([
            "success" => true
        ]);
    }

    public function verifyAccount()
    {
    }

    public function resendVerification()
    {
    }
}
