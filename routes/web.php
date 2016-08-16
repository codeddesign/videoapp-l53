<?php

// Home Page.
Route::get('/', ['uses' => 'HomeController@index']);

// login
Route::get('login', 'Authentication\LoginController@showLoginForm')->name('login');
Route::post('login', 'Authentication\LoginController@login');
Route::get('logout', 'Authentication\LoginController@logout');

//verification
Route::get('verify/phone', 'Authentication\LoginController@verifyPhone')->name('verify.phone');
Route::get('verify/email', 'Authentication\LoginController@verifyEmail')->name('verify.email');

//registration
Route::get('register', 'Authentication\RegistrationController@showRegistrationForm');
Route::post('register', 'Authentication\RegistrationController@register');

//// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');



// Vue app.
Route::get('/app', ['uses' => 'PagesController@index'])->name('app')->middleware('auth', 'verifyaccount');
