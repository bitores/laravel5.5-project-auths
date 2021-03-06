<?php

return [

    /*服务端口，对外开放*/
    'port'     => env('WS_SERVER_PORT', '7272'),                   //客户端连接这个端口

    /*注册中心配置*/
    'register' => [
        'host' => env('WS_REGISTER_HOST', '127.0.0.1'),            //地址
        'port' => env('WS_REGISTER_PORT', '1236'),                 //端口
    ],

    /*worker配置*/
    'worker'   => [
        'name'  => env('WS_WORKER_NAME', 'ChatBusinessWorker'),        //名称
        'count' => env('WS_WORKER_COUNT', '4'),                    //进程数量
    ],

    /*gateway配置*/
    'gateway'  => [
        'name'      => env('WS_GATEWAY_NAME', 'ChatGateway'),          //名称
        'count'     => env('WS_GATEWAY_COUNT', '4'),               //进程数量
        'lan_ip'    => env('WS_GATEWAY_LAN_IP', '127.0.0.1'),      //局域网络地址
        'startPort' => env('WS_GATEWAY_START_PORT', '4000'),       //开始端口
        'fromDomain' => env('WS_GATEWAY_DOMAIN', 'http://my.dev')
    ],
];