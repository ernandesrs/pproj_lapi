<?php

use App\Http\Controllers\Auth\AccountController;

use App\Http\Controllers\Me\MeController;
use App\Http\Controllers\Me\AddressController as MeAddressController;

use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

use App\Http\Controllers\Dash\DashController as DashDashController;

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
            "success" => true,
            "status" => "Conected with " . config('app.name') . "! :D"
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
                    Route::post("/update/email", [MeController::class, "requestEmailUpdate"]);
                    Route::patch("/update/email/{token}", [MeController::class, "emailUpdate"]);
                    Route::post("/photo-upload", [MeController::class, "photoUpload"]);
                    Route::delete("/photo-delete", [MeController::class, "photoDelete"]);
                    Route::delete("/delete", [MeController::class, "delete"]);

                    /**
                     * ADDRESSES CONTROLLER
                     */
                    Route::apiResource("addresses", MeAddressController::class);
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
             * NOTIFICATION CONTROLLER
             */
            Route::get('/notifications/unread', [AdminNotificationController::class, 'unread']);
            Route::apiResource("notifications", AdminNotificationController::class)->except([
                'store',
                'update'
            ]);
            Route::put('/notifications/{id}/mark-as-read', [AdminNotificationController::class, 'markAsRead']);
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

            Route::get("/", [DashDashController::class, "index"]);

        }
    );
});