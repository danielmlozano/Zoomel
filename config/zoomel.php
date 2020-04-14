<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Zoom API config
    |--------------------------------------------------------------------------
    |
    / This values serve the API credentials for the APP
    |
    */

    'zoom_client_id' => env('ZOOM_CLIENT_ID',''),
    'zoom_client_secret' => env('ZOOM_CLIENT_SECRET',''),

    /*
    |--------------------------------------------------------------------------
    | Redirect App URI
    |--------------------------------------------------------------------------
    |
    / This URL is used to redirect the user to the app, once a Zoom authentication
    / process is completed and succesful.
    |
    */

    'oauth_redirect_uri' => env('ZOOM_OAUTH_REDIRECT_URI','/zoomel/oauth'),

    /*
    |--------------------------------------------------------------------------
    | App users table
    |--------------------------------------------------------------------------
    |
    / The name of the table the app uses for manage users
    |
    */

    'user_table' => 'users',

    /*
    |--------------------------------------------------------------------------
    | User model
    |--------------------------------------------------------------------------
    |
    / The Laravel Model used in the app
    |
    */

    'user_model' => App\User::class,
];
