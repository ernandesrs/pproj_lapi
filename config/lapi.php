<?php

return [
    'url_front' => env('APP_URL_FRONT', 'http://localhost'),

    /**
     * 
     * URL(frontend) for password update.
     * A verification token will be attached to this URL and emailed to the user.
     * 
     * It will be something like this: https://frontend.com/auth/update-password?token=0vds09dsd90s
     * 
     */
    'url_front_password_reset' => env('APP_URL_FRONT') . '/auth/update-password',

    /**
     * 
     * URL(frontend) for user account verify.
     * A verification token will be attached to this URL and emailed to the user.
     * 
     * It will be something like this: https://frontend.com/auth/verify-account?token=0vds09dsd90s
     * 
     */
    'url_front_user_verify' => env('APP_URL_FRONT') . '/auth/verify-account',

    /**
     * 
     * URL(frontend) for email update.
     * A verification token will be attached to this URL and emailed to the user.
     * 
     * It will be something like this: https://frontend.com/me/update/email?token=0vds09dsd90s
     * 
     */
    'url_front_user_email_update' => env('APP_URL_FRONT') . '/me/update/email',

    /**
     * 
     * URL (frontend) that should be called after user authentication with the social network.
     * This url will contain the authorization token that must be stored in a cookie on the frontend.
     * 
     * It will be something like this: https://frontend.com/login/social-login?token=Bearer%20...&expire_in_minutes=4400
     * 
     */
    'url_front_social_login_callback' => env('APP_URL_FRONT') . '/auth/login/social-login'
];