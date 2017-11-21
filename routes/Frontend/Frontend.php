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

        Route::group([
            'middleware' => 'access.routeNeedsRole:demandside;auditorside;producerside', // 
        ], function () {

            Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        });


        Route::group([
            'middleware' => 'access.routeNeedsRole:User', // 
        ], function () {

            Route::get('bindrole', 'DashboardController@setRole')->name('userdashboard');
        //      // bind role for mlm
            Route::post('bind/role', 'DashboardController@bindRole')->name('bind.role');
        });

        

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

        /*
         * 后面是普通业务功能页面 
         */

        
    });


    Route::group(['namespace' => 'MLM', 'as' => 'mlm.'], function () {


        // 需求方权限设置
        Route::group([
            'middleware' => 'access.routeNeedsRole:demandside', // 
        ], function () {

            // 需求方业务
            //  需求方产品
            Route::get('products', 'DemandsideController@index')->name('demandside.index');
            // 需求方 新建产品
            Route::get('product/create', 'DemandsideController@create')->name('demandside.product.create');
            // 需求方 编辑产品
            Route::get('product/{productid}/edit', 'DemandsideController@edit')->name('demandside.product.edit');
            // 品牌创建
            Route::post('brand/create', 'UBrandController@create')->name('demandside.brand.create');
            // 产品 删除
            Route::post('product/del', 'DemandsideController@del')->name('demandside.product.del');
            // 产品 发布
            Route::post('product/posttask', 'DemandsideController@postTask')->name('demandside.product.posttask');
            // 产品 取消
            Route::post('product/canceltask', 'DemandsideController@cancelTask')->name('demandside.product.canceltask');
            // 所有产品
            Route::post('products', 'DemandsideController@table')->name('demandside.products.get');
            // 产品信息保存
            Route::post('product/save', 'DemandsideController@save')->name('demandside.product.save');
            // 产品提交审核
            Route::post('product/submit', 'DemandsideController@submit')->name('demandside.product.submit');
            // 产品创建并提交审核
            Route::post('product/oncesubmit', 'DemandsideController@oncesubmit')->name('demandside.product.oncesubmit');
        });

        


        // 审核方权限设置
        Route::group([
            'middleware' => 'access.routeNeedsRole:auditorside', // 
        ], function () {

            // 审核方业务
            Route::get('auditor', 'AuditorController@index')->name('auditor.index');
            // 审核 需求列表
            Route::get('auditor/demandlist', 'AuditorController@demands')->name('auditor.demandlist');
            // 审核 模型列表
            Route::get('auditor/modellist', 'AuditorController@models')->name('auditor.modellist');

            // 审核需求

            // 获取 需求信息
            Route::post('auditor/products', 'AuditorController@demandsidproducts')->name('auditor.product.list'); 
            // 需求信息 不通过
            Route::post('auditor/product/nopass', 'AuditorController@nopass')->name('auditor.product.nopass'); 
            // 需求信息 通过
            Route::post('auditor/product/pass', 'AuditorController@pass')->name('auditor.product.pass');

            // 审核模型
            // 获取 模型信息
            Route::post('auditor/models', 'AuditorController@producermodels')->name('auditor.model.list'); 
            // 模型信息 不通过
            Route::post('auditor/model/nopass', 'AuditorController@modelnopass')->name('auditor.model.nopass'); 
            // 模型信息 通过
            Route::post('auditor/model/pass', 'AuditorController@modelpass')->name('auditor.model.pass');
        });

        // 制作方权限设置
        Route::group([
            'middleware' => 'access.routeNeedsRole:producerside', // 
        ], function () {

            // 制作方业务
            Route::get('producer', 'MakerController@index')->name('producer.index');
            // 需求池中 产品
            Route::get('producer/demandlist', 'MakerController@demandlist')->name('producer.demandlist');
            // 获取  所有 需求池中 产品
            Route::post('producer/products', 'MakerController@tasks')->name('producer.product.alltasks');
            // 接受 订单
            Route::post('producer/product/order', 'MakerController@order')->name('producer.product.order'); 
            // 取消 订单
            Route::post('producer/product/cancelorder', 'MakerController@cancelorder')->name('producer.product.cancelorder');
            // 模型
            Route::post('producer/product/model', 'MakerController@model')->name('producer.product.model');
            // 获取所有任务
            Route::post('producer/tasks', 'MakerController@minetasks')->name('producer.product.tasks');
             
        });


        // 建模教程
        Route::get('tutorial/modeling', 'MakerController@modelingTutorial')->name('producer.tutorial.modeling');
        // 审核教程
        Route::get('tutorial/review', 'MakerController@reviewTutorial')->name('producer.tutorial.review'); 
        // 需求 文档说明
        Route::get('readme', 'DemandsideController@readme')->name('demandside.readme');
        // 产品 信息展示页
        Route::get('product/{productid}/show', 'DemandsideController@show')->name('demandside.product.show');
        // 产品 需求审核 结果
        Route::get('product/{productid}/assessment', 'DemandsideController@assessment')->name('demandside.product.assessment');
        // 产品 模型审核 结果
        Route::get('producer/product/{productid}/assessment', 'ProducerController@assessment')->name('producer.product.assessment');
        // 下载 需求资料包
        Route::post('product/download', 'ProductController@download')->name('demandside.product.download');
        // 下载 模型包
        Route::post('product/downloadmodel', 'ProductController@downloadmodel')->name('proceder.product.download');

        //---------api
        // 获取 需求 修改意见内容
        Route::post('demandside/product/review', 'ProductController@reviewComments')->name('demandside.product.review'); 
        // 获取 模型 修改意见内容
        Route::post('producer/model/review', 'ProductController@modelreviewComments')->name('producer.model.review');
        // 图片，模型等文件 上传
        Route::post('mlmfiles/upload', 'UploadController@index')->name('mlmfiles.upload');
        
    });
});
