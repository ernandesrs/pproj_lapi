<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Exceptions\Account\LoginFailException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\LoginRequest;
use App\Http\Requests\Request\AccountRequest;
use App\Services\Account\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @param Request $request
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
                "expire_in_minutes" => config("jwt.ttl")
            ]
        ]);
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
        $user = $this->accountService->register(
            $request->validated()
        );

        event(new UserRegistered($user));

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
