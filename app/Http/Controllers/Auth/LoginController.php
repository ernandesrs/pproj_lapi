<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Account\LoginFailException;
use App\Exceptions\Auth\LoginWithGoogleFailException;
use App\Exceptions\Auth\SocialLoginEmailAlreadyRegisteredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * User services
     *
     * @var \App\Services\UserService
     */
    private $userService;

    /**
     * Google Login
     *
     * @var \League\OAuth2\Client\Provider\Google
     */
    private $google;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userService = new UserService();

        if (env('OAUTH2_GOOGLE_CLIENT_ID') && env('OAUTH2_GOOGLE_CLIENT_SECRET')) {
            $this->google = new \League\OAuth2\Client\Provider\Google([
                'clientId' => env('OAUTH2_GOOGLE_CLIENT_ID'),
                'clientSecret' => env('OAUTH2_GOOGLE_CLIENT_SECRET'),
                'redirectUri' => route('auth.social.googleCallback')
            ]);
        }
    }

    /**
     * Login social
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSocialAuthorizationUris()
    {
        return response()->json([
            'success' => true,
            'socials' => [
                'google' => $this->google ? $this->google->getAuthorizationUrl() : null
            ]
        ]);
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $token = \Auth::attempt([
            "email" => $validated["email"],
            "password" => $validated["password"]
        ]);

        if (!$token) {
            throw new LoginFailException();
        }

        return response()->json([
            "success" => true,
            "user" => new UserResource(\Auth::user()),
            "access" => $this->access($token)
        ]);
    }

    /**
     * Login with Google callback: this method handles the Google call
     * with the access authorization response
     *
     * @param Request $request
     * @return null|\Illuminate\Http\JsonResponse
     */
    public function loginWithGoogle(Request $request)
    {
        if (!$request->get('error') && !$request->get('code')) {
            return null;
        }

        if ($request->get('error')) {
            throw new LoginWithGoogleFailException;
        }

        $googleToken = $this->google->getAccessToken('authorization_code', ['code' => $request->get('code')]);
        $googleUser = $this->google->getResourceOwner($googleToken);

        $user = User::where('email', $googleUser->getEmail())->first();
        if (!$user) {
            $user = $this->userService->register([
                'first_name' => $googleUser->getFirstName(),
                'last_name' => $googleUser->getLastName(),
                'username' => $googleUser->getName() . '_' . uniqid(),
                'gender' => 'n',
                'email' => $googleUser->getEmail()
            ]);

            $user->google_id = $googleUser->getId();
            $user->verification_token = null;
            $user->email_verified_at = \Illuminate\Support\Carbon::now();
            $user->save();
        }

        if ($user && !$user->google_id) {
            throw new SocialLoginEmailAlreadyRegisteredException;
        }

        $token = \Auth::login($user);

        return $this->loginSocialAccess($token);
    }

    /**
     * Login with social network redirect with autorization data
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    private function loginSocialAccess($token)
    {
        return response()
            ->redirectTo(
                config('lapi.url_front_social_login_callback') . '?' . http_build_query($this->access($token))
            );
    }

    /**
     * Authorization data access
     *
     * @param string $token
     * @return array
     */
    private function access(string $token)
    {
        return [
            "token" => $token,
            "type" => "Bearer",
            "full" => "Bearer " . $token,
            "expire_in_minutes" => config("jwt.ttl")
        ];
    }

    /**
     * Logout
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        \Auth::logout();

        return response()->json([
            "success" => true
        ]);
    }
}