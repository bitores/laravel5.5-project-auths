<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');
Route::get('macros', 'FrontendController@macros')->name('macros');
Route::get('contact', 'ContactController@index')->name('contact');
Route::post('contact/send', 'ContactController@send')->name('contact.send');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        Route::post('uploadAvatar', 'AccountController@uploadAvatar');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');

        Route::post('mobile/bind', 'MobileBindController@bind')->name('mobile.bind');

        // Route::post('email/bind', 'EmailBindController@bind')->name('email.bind');
        // Route::get('email/confirm/{token}', 'EmailBindController@confirm')->name('email.confirm');

        /*
         * 后面是普通业务功能页面 
         */

        // 权限设置
        Route::group([
            'middleware' => 'access.routeNeedsRole:demandside', // 
        ], function () {
        });


        // 需求方业务
        Route::get('products', 'DemandsideController@index')->name('demandside.index');
        Route::get('readme', 'DemandsideController@readme')->name('demandside.readme');

        Route::get('product/create', 'DemandsideController@create')->name('demandside.product.create');
        Route::get('product/{productid}/edit', 'DemandsideController@edit')->name('demandside.product.edit');
        Route::get('product/{productid}/show', 'DemandsideController@show')->name('demandside.product.show');
        Route::get('product/assessment', 'DemandsideController@assessment')->name('demandside.product.assessment');

           
        
        // 制作方业务
        Route::get('producer', 'ProducerController@index')->name('producer.index');
        Route::get('producer/tutorial/modeling', 'ProducerController@modelingTutorial')->name('producer.tutorial.modeling');
        Route::get('producer/tutorial/review', 'ProducerController@reviewTutorial')->name('producer.tutorial.review');

        Route::get('producer/product/show', 'ProducerController@show')->name('producer.product.show');
        Route::get('producer/product/assessment', 'ProducerController@assessment')->name('producer.product.assessment');


        // 审核方业务
        Route::get('auditor', 'AuditorController@index')->name('auditor.index');
        Route::get('auditor/demandlist', 'AuditorController@demands')->name('auditor.demandlist');
        Route::get('auditor/modellist', 'AuditorController@models')->name('auditor.modellist');






        //---------api
        Route::post('products', 'ProductController@table')->name('demandside.products.get');
        Route::post('demandside/brand/create', 'BrandController@create')->name('demandside.brand.create');
        Route::post('demandside/product/save', 'ProductController@save')->name('demandside.product.save');
        Route::post('demandside/product/upload', 'UploadController@index')->name('demandside.product.upload');
    });
});
