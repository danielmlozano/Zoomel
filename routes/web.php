<?php

use Illuminate\Support\Facades\Route;

Route::get('access','AuthController@oauthAccessRequest')->name('access');
Route::get('oauth','AuthController@oauthAccessResponse')->name('oauth');
