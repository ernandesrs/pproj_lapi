<?php

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Dash\Payment\PaymentMethodController as DashPaymentMethodController;
use App\Http\Controllers\Dash\Payment\CardController as DashCardController;
use App\Http\Controllers\Dash\SubscriptionController as DashSubscriptionController;
use App\Http\Controllers\Dash\PackageController as DashPackageController;
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

/**
 * APP ENDPOINTS - VERSION 1
 */
Route::group([
    "prefix" => "v1"
], function () {

    Route::get("/", function () {
        return response()->json([
            "success" => true
        ]);
    });

    /**
     * AUTH ENDPOINTS
     */
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
                }
            );

            Route::get("/verify-account", [AccountController::class, "verifyAccount"]);

            Route::middleware("auth")->group(
                function () {
                    Route::get("/logout", [AccountController::class, "logout"]);
                    Route::get("/resend-verification", [AccountController::class, "resendVerification"]);
                }
            );
        }
    );

    /**
     * ME(profile) ENDPOINTS
     */
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

    /**
     * ADMIN ENDPOINTS
     */
    Route::group(
        [
            "prefix" => "admin",
            "middleware" => ["auth", "admin"]
        ],
        function () {
            Route::get('/', [AdminAdminController::class, "index"]);

            /**
             * ROLE CONTROLLER
             */
            Route::apiResource("roles", AdminRoleController::class);

            /**
             * USER CONTROLLER
             */
            Route::apiResource("users", AdminUserController::class);
            Route::delete("/users/{user}/photo-delete", [AdminUserController::class, "photoDelete"]);
            Route::put("/users/{user}/update-level", [AdminUserController::class, "updateLevel"]);
            Route::put("/users/roles/{user}/{role}", [AdminUserController::class, "roleUpdate"]);
            Route::delete("/users/roles/{user}/{role}", [AdminUserController::class, "roleDelete"]);

            /**
             * PACKAGE CONTROLLER
             */
            Route::apiResource("packages", AdminPackageController::class);

            /**
             * SUBSCRIPTION CONTROLLER
             */
            Route::apiResource("subscriptions", AdminSubscriptionController::class);
        }
    );

    /**
     * DASH(user/client area) ENDPOINTS
     */
    Route::group(
        [
            "prefix" => "dash",
            "middleware" => ["auth"]
        ],
        function () {
            Route::get("/payment-methods", [DashPaymentMethodController::class, "index"]);
            Route::put("/payment-methods", [DashPaymentMethodController::class, "update"]);
            Route::middleware(["throttle:card_registration_attempt_limit"])
                ->post("/payment-methods/cards", [DashCardController::class, "store"]);
            Route::put("/payment-methods/cards/{id}", [DashCardController::class, "update"]);
            Route::delete("/payment-methods/cards/{id}", [DashCardController::class, "destroy"]);

            Route::apiResource("subscriptions", DashSubscriptionController::class)->except(["update", "destroy"]);
            Route::patch("/subscriptions/{subscription_id}/cancel", [DashSubscriptionController::class, "cancel"]);
            Route::get("/subscriptions/show/active", [DashSubscriptionController::class, "active"]);

            Route::apiResource("packages", DashPackageController::class);
        }
    );
});