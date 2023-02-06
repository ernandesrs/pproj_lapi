<?php

use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Dash\CreditCardController as DashCreditCardController;
use App\Http\Controllers\Dash\SubscriptionController as DashSubscriptionController;
use App\Http\Controllers\Me\MeController;
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

    Route::group(
        [
            "prefix" => "auth"
        ],
        function () {
            Route::middleware("guest")->group(
                function () {
                        Route::post("/login", [AccountController::class, "login"]);
                        Route::post("/forget-password", [AccountController::class, "forgetPassword"]);
                        Route::put("/update-password", [AccountController::class, "updatePassword"]);
                        Route::post("/register", [AccountController::class, "register"]);
                        Route::get("/verify-account", [AccountController::class, "verifyAccount"]);
                    }
            );

            Route::middleware("auth")->group(
                function () {
                        Route::get("/logout", [AccountController::class, "logout"]);
                        Route::get("/resend-verification", [AccountController::class, "resendVerification"]);
                    }
            );
        }
    );

    Route::group(
        [
            "prefix" => "me"
        ],
        function () {

            Route::middleware("auth")->group(
                function () {
                        Route::get("/", [MeController::class, "me"]);
                        Route::put("/update", [MeController::class, "update"]);
                        Route::post("/photo-upload", [MeController::class, "photoUpload"]);
                        Route::delete("/photo-delete", [MeController::class, "photoDelete"]);
                        Route::delete("/delete", [MeController::class, "delete"]);
                    }
            );

            Route::put("/recovery", [MeController::class, "recovery"]);
        }
    );

    Route::group(
        [
            "prefix" => "admin",
            "middleware" => ["auth", "admin"]
        ],
        function () {
            Route::get(
                "/",
                function () {
                        return response()->json([
                            "success" => true
                        ]);
                    }
            );

            /**
             * ROLE CONTROLLER
             */
            Route::apiResource("roles", AdminRoleController::class);

            /**
             * USER CONTROLLER
             */
            Route::apiResource("users", AdminUserController::class);
            Route::delete("/users/{user}/photo-delete", [AdminUserController::class, "photoDelete"]);
            Route::put("/users/{user}/promote", [AdminUserController::class, "promote"]);
            Route::put("/users/{user}/demote", [AdminUserController::class, "demote"]);
            Route::put("/users/{user}/{role}", [AdminUserController::class, "roleUpdate"]);
            Route::delete("/users/{user}/{role}", [AdminUserController::class, "roleDelete"]);
        }
    );

    Route::group(
        [
            "prefix" => "dash",
            "middleware" => ["auth"]
        ],
        function () {
            Route::apiResource("credit-cards", DashCreditCardController::class);

            Route::apiResource("subscriptions", DashSubscriptionController::class);
        }
    );
});