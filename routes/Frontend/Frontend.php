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
        Route::post('email/bind', 'EmailBindController@sendBindingEmail')->name('bind.email');
        Route::get('email/confirm/{token}', 'EmailBindController@bindEmail')->name('email.bind');

        // Route::post('email/bind', 'EmailBindController@bind')->name('email.bind');
        // Route::get('email/confirm/{token}', 'EmailBindController@confirm')->name('email.confirm');

        /*
         * 后面是普通业务功能页面 
         */

        
    });


    Route::group(['namespace' => 'MLM', 'as' => 'mlm.'], function () {
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
        Route::get('product/{productid}/assessment', 'DemandsideController@assessment')->name('demandside.product.assessment');
        Route::post('product/download', 'ProductController@download')->name('demandside.product.download');

           
        
        // 制作方业务
        Route::get('producer', 'ProducerController@index')->name('producer.index');
        Route::get('producer/demandlist', 'ProducerController@demandlist')->name('producer.demandlist');
        Route::get('producer/tutorial/modeling', 'ProducerController@modelingTutorial')->name('producer.tutorial.modeling');
        Route::get('producer/tutorial/review', 'ProducerController@reviewTutorial')->name('producer.tutorial.review');

        Route::get('producer/product/show', 'ProducerController@show')->name('producer.product.show');
        Route::get('producer/product/assessment', 'ProducerController@assessment')->name('producer.product.assessment');


        // 审核方业务
        Route::get('auditor', 'AuditorController@index')->name('auditor.index');
        Route::get('auditor/demandlist', 'AuditorController@demands')->name('auditor.demandlist');
        Route::get('auditor/modellist', 'AuditorController@models')->name('auditor.modellist');






        //---------api
        Route::post('demandside/products', 'ProductController@table')->name('demandside.products.get');
        Route::post('demandside/brand/create', 'BrandController@create')->name('demandside.brand.create');
        Route::post('demandside/product/save', 'ProductController@save')->name('demandside.product.save');
        Route::post('demandside/product/submit', 'ProductController@submit')->name('demandside.product.submit');
        Route::post('demandside/product/oncesubmit', 'ProductController@oncesubmit')->name('demandside.product.oncesubmit');
        Route::post('demandside/product/upload', 'UploadController@index')->name('demandside.product.upload'); 

        Route::post('auditor/products', 'ProductController@demandsidproducts')->name('demandside.product.list'); 
        Route::post('auditor/product/nopass', 'ProductController@nopass')->name('demandside.product.nopass'); 
        Route::post('auditor/product/pass', 'ProductController@pass')->name('demandside.product.pass'); 

         Route::post('auditor/model/nopass', 'ProductController@nopass')->name('producer.product.nopass'); 
        Route::post('auditor/model/pass', 'ProductController@pass')->name('producer.product.pass'); 

        Route::post('demandside/product/del', 'ProductController@del')->name('demandside.product.del'); 
        Route::post('demandside/product/posttask', 'ProductController@postTask')->name('demandside.product.posttask'); 
        Route::post('demandside/product/canceltask', 'ProductController@cancelTask')->name('demandside.product.canceltask'); 
        Route::post('demandside/product/review', 'ProductController@reviewComments')->name('demandside.product.review'); 

        Route::post('producer/products', 'ProductController@tasks')->name('demandside.product.tasks');
        Route::post('producer/product/order', 'ProductController@order')->name('producer.product.order'); 
        Route::post('producer/product/cancelorder', 'ProductController@cancelorder')->name('producer.product.cancelorder'); 

        Route::post('producer/tasks', 'ProductController@minetasks')->name('producer.product.tasks');
    });
});
