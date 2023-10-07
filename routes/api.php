<?php

use App\Http\Controllers\Auth\AccountController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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
                    /**
                     * 
                     * * LOGIN
                     * 
                     */

                    /**
                     * login with email and password
                     */
                    Route::post("/login", [LoginController::class, "login"]);

                    /**
                     * get uris to authorization
                     */
                    Route::get("/login/social-uris", [LoginController::class, "getSocialAuthorizationUris"]);

                    /**
                     * google callback
                     */
                    Route::get("/login/social/google-callback", [LoginController::class, "loginWithGoogle"])
                        ->middleware(['demo_disable_resource'])->name('auth.social.googleCallback');

                    /**
                     * 
                     * * FORGOT PASSWORD
                     * 
                     */
                    Route::post("/forget-password", [ForgotPasswordController::class, "forgetPassword"])->middleware(['demo_disable_resource']);
                    Route::put("/update-password", [ForgotPasswordController::class, "updatePassword"]);

                    /**
                     * 
                     * ACCOUNT
                     * 
                     */
                    Route::post("/register", [AccountController::class, "register"])->middleware(['demo_disable_resource']);
                }
            );

            Route::middleware("auth")->group(
                function () {
                    /**
                     * 
                     * VERIFICATION
                     * 
                     */
                    Route::get("/verify-account", [VerificationController::class, "verifyAccount"]);
                    Route::get("/resend-verification", [VerificationController::class, "resendVerification"])->middleware(['demo_disable_resource']);

                    /**
                     * 
                     * LOGOUT
                     * 
                     */
                    Route::get("/logout", [LoginController::class, "logout"]);
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