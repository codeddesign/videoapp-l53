<?php

// Home Page.
Route::get('/', ['uses' => 'HomeController@index']);

// Login.
Route::get('login', 'Authentication\LoginController@showLoginForm')->name('login');
Route::post('login', 'Authentication\LoginController@login');
Route::get('logout', 'Authentication\LoginController@logout');

// Verification.
// @todo implementation.
Route::get('verify/phone', 'Authentication\VerificationsController@verifyPhone')->name('verify.phone');
Route::post('verify/phone', 'Authentication\VerificationsController@postVerifyPhone');
Route::post('verify/phone/code', 'Authentication\VerificationsController@postVerifyPhoneCode');
Route::get('verify/email', 'Authentication\VerificationsController@verifyEmail')->name('verify.email');
Route::get('verify/email/{token}', 'Authentication\VerificationsController@verifyEmailToken')->name('verify.email.token');

// Registration.
Route::get('register', 'Authentication\RegistrationController@showRegistrationForm');
Route::post('register', 'Authentication\RegistrationController@register');

// Password Reset Routes.
// @todo fix this.
Route::get('password/reset', 'Authentication\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Authentication\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Authentication\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Authentication\ResetPasswordController@reset');

// Vue app.
Route::get('/app', ['uses' => 'PagesController@index'])->name('app')->middleware('auth', 'verifyaccount');

// Public endpoint, used by the player.
Route::get('/campaign/{id}', 'CampaignsController@campaign');
Route::get('/track', 'TrackController@index');
Route::get('/plugin', 'PluginController@CampaignAdd');

Route::group(['prefix' => '/public/api', 'namespace' => 'Client'], function () {
    Route::get('/campaign/{id}', 'CampaignsController')->middleware('cors');
    Route::get('/track', 'TrackingController@index');
    Route::get('/plugin', 'PluginController@CampaignAdd');
});
