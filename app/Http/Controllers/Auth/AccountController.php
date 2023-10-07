<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountRequest;
use App\Services\UserService;

class AccountController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Register an user
     *
     * @param AccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AccountRequest $request)
    {
        $this->userService->register($request->validated());

        return response()->json([
            "success" => true
        ]);
    }
}