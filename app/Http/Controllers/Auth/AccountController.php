<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function register(Request $request)
    {
        dd($request->all());
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
