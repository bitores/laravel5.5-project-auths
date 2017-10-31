<?php

/**
 * Frontend Access Controllers
 * All route names are prefixed with 'frontend.auth'.
 */
Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {

    /*
     * These routes require the user to be logged in
     */
    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'LoginController@logout')->name('logout');

        //For when admin is logged in as user from backend
        Route::get('logout-as', 'LoginController@logoutAs')->name('logout-as');

        // Change Password Routes
        Route::patch('password/change', 'ChangePasswordController@changePassword')->name('password.change');
    });

    /*
     * These routes require no user to be logged in
     */
    Route::group(['middleware' => 'guest'], function () {
        // Authentication Routes
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.post');

        // Socialite Routes
        Route::get('login/{provider}', 'SocialLoginController@login')->name('social.login');

        // Registration Routes
        if (config('access.users.registration')) {
            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register')->name('register.post');
            Route::post('register_mobile', 'RegisterMobileController@register')->name('register_mobile.post');

            
        }

        // Confirm Account Routes
        Route::get('account/confirm/{token}', 'ConfirmAccountController@confirm')->name('account.confirm');
        Route::get('account/confirm/resend/{user}', 'ConfirmAccountController@sendConfirmationEmail')->name('account.confirm.resend');

        // Password Reset Routes
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.email');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email.post');

        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset');


        // add by huangzj 2017-10-31
        #Route::post('password2/email', 'Auth\PasswordController@sendResetLinkEmail');  
        //通过邮件重置密码
        #Route::post('password2/reset-mail', 'Auth\PasswordController@resetBymail');  
        //发送手机短信验证码
        #Route::post('password2/phone', 'Auth\PasswordController@sendResetCodePhone');  
        //通过手机验证码找回密码
        #Route::post('password2/reset-phone', 'Auth\PasswordController@resetByPhone');
    });
});