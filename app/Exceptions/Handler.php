<?php

namespace App\Exceptions;

use App\Exceptions\Account\LoginFailException;
use App\Exceptions\Account\UpdatePasswordTokenInvalidException;
use App\Exceptions\Account\VerificationTokenInvalidException;
use App\Exceptions\Admin\HasDependentsException;
use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Exceptions\Auth\UnauthenticatedException;
use App\Exceptions\Dash\HasActiveSubscriptionException;
use App\Exceptions\Dash\Pagarme\ChargebackPaymentException;
use App\Exceptions\Dash\Pagarme\RefundedPaymentException;
use App\Exceptions\Dash\Pagarme\RefusedPaymentException;
use App\Exceptions\Dash\PaymentFailException;
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
        VerificationTokenInvalidException::class,
        LoginFailException::class,
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

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
