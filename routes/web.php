<?php

// Home Page.
Route::get('/', ['uses' => 'HomeController@index'])->name('home');
Route::post('signup', 'HomeController@signup');

// Contact
Route::get('contact', 'HomeController@getContact');
Route::post('contact', 'HomeController@postContact');

// Company
Route::get('company', 'HomeController@getCompany');
// Route::get('compoany/live', 'HomeController@getCompanyLive');

// CacheQ
Route::get('cacheq', 'HomeController@getCacheq');

// CacheQ
Route::get('ad-serving', 'HomeController@getAdServing');

// Demo
Route::get('demo/{mode?}', 'HomeController@demo');

// Login.
Route::get('login', 'Authentication\LoginController@showLoginForm')->name('login');
Route::post('login', 'Authentication\LoginController@login');
Route::get('logout', 'Authentication\LoginController@logout');

// Verification.
Route::get('verify/phone', 'Authentication\VerificationsController@verifyPhone')->name('verify.phone');
Route::post('verify/phone', 'Authentication\VerificationsController@postVerifyPhone');
Route::post('verify/phone/code', 'Authentication\VerificationsController@postVerifyPhoneCode');
Route::get('verify/email', 'Authentication\VerificationsController@verifyEmail')->name('verify.email');
Route::get('verify/email/{token}', 'Authentication\VerificationsController@verifyEmailToken')->name('verify.email.token');

// Registration.
Route::get('register', 'Authentication\RegistrationController@showRegistrationForm');
Route::post('register', 'Authentication\RegistrationController@register');

// Password Reset Routes.
Route::get('password/reset', 'Authentication\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Authentication\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Authentication\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Authentication\ResetPasswordController@reset');

// Vue app.
Route::get('/app/{vue?}', ['as' => 'app', 'uses' => 'PagesController@index'])->where('vue', '[\/\w\.-]*');

Route::get('domainlist', 'DomainListController@domains');
