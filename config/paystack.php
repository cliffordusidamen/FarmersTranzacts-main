<?php


/*
|--------------------------------------------------------------------------
| Configuration for Paystack Payment Gateway
|--------------------------------------------------------------------------
|
| This value is the name of your application. This value is used when the
| framework needs to place the application's name in a notification or
| any other location as required by the application or its packages.
|
*/
return [

    /*
    |--------------------------------------------------------------------------
    | SECRET KEY
    |--------------------------------------------------------------------------
    |
    */
    'secret_key' => env('PAYSTACK_SECRET_KEY', 'secret_key'),

    /*
    |--------------------------------------------------------------------------
    | SECRET KEY
    |--------------------------------------------------------------------------
    |
    */
    'public_key' => env('PAYSTACK_PUBLIC_KEY', 'secret_key'),

];