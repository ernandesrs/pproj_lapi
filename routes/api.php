<?php

use App\Http\Controllers\Auth\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    "prefix" => "v1"
], function () {

    Route::group([
        "prefix" => "auth"
    ], function () {
        Route::middleware("guest")->group(function () {
            Route::post("/login", [AccountController::class, "login"]);
            Route::post("/forget-password", [AccountController::class, "forgetPassword"]);
            Route::post("/update-password", [AccountController::class, "updatePassword"]);
            Route::post("/register", [AccountController::class, "register"]);
        });

        Route::middleware("auth")->group(function () {
            Route::get("/logout", [AccountController::class, "logout"]);
            Route::get("/verify-account", [AccountController::class, "verifyAccount"]);
            Route::get("/resend-verification", [AccountController::class, "resendVerification"]);
        });
    });
});
