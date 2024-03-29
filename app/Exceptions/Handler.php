<?php

namespace App\Exceptions;

use App\Exceptions\Account\LoginFailException;
use App\Exceptions\Account\UpdatePasswordTokenInvalidException;
use App\Exceptions\Account\VerificationTokenInvalidException;
use App\Exceptions\Admin\HasDependentsException;
use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Exceptions\Auth\LoginWithGoogleFailException;
use App\Exceptions\Auth\SocialLoginEmailAlreadyRegisteredException;
use App\Exceptions\Auth\UnauthenticatedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        AppDemoException::class,

        VerificationTokenInvalidException::class,

        LoginFailException::class,
        LoginWithGoogleFailException::class,
        SocialLoginEmailAlreadyRegisteredException::class,

        UpdatePasswordTokenInvalidException::class,

        NotHaveAdminPanelAcessException::class,
        UnauthorizedActionException::class,
        HasDependentsException::class,

        UnauthenticatedException::class,

        InvalidDataException::class,
        NotFoundException::class,
        UnauthorizedException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                throw new NotFoundException();
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                throw new UnauthorizedActionException();
            }
        });

        $this->renderable(function (LoginWithGoogleFailException $e, $request) {
            return response()
                ->redirectTo(
                    config('lapi.url_front_social_login_callback') . '?error=' . 'LoginWithGoogleFailException'
                );
        });

        $this->renderable(function (SocialLoginEmailAlreadyRegisteredException $e, $request) {
            return response()
                ->redirectTo(
                    config('lapi.url_front_social_login_callback') . '?error=' . 'SocialLoginEmailAlreadyRegisteredException'
                );
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}