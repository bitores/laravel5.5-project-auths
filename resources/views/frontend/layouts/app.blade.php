<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
        <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        @langRTL
            {{ Html::style(getRtlCss(mix('css/frontend.css'))) }}
        @else
            {{ Html::style(mix('css/frontend.css')) }}
        @endif

        @yield('after-styles')
        <script>
            

            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body id="app-layout">
        <div id="app">
            @include('includes.partials.logged-in-as')
            @include('frontend.includes.nav')

            <div class="container">
                @include('includes.partials.messages')
                @yield('content')
            </div><!-- container -->
        </div><!--#app-->

        <!-- Scripts -->
        @yield('before-scripts')
        {!! Html::script(mix('js/frontend.js')) !!}
        @yield('after-scripts')

        <!-- chat -->
        <script type="text/javascript" src="/js/libs/workerman/swfobject.js"></script>

        <script type="text/javascript" src="/js/libs/workerman/web_socket.js"></script>
        <!-- <script type="text/javascript" src="/js/libs/workerman/jquery-sinaEmotion-2.1.0.min.js"></script> -->
        <script type="text/javascript">
        // $(function(){
            select_client_id = 'all';
            $("#client_list").change(function(){
                 select_client_id = $("#client_list option:selected").attr("value");
            });
            $('.face').click(function(event){
                $(this).sinaEmotion();
                event.stopPropagation();
            });
        

            if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
            // 如果浏览器不支持websocket，会使用这个flash自动模拟websocket协议，此过程对开发者透明
            WEB_SOCKET_SWF_LOCATION = "/js/libs/workerman/WebSocketMain.swf";
            // 开启flash的websocket debug
            WEB_SOCKET_DEBUG = true;
            var ws, name, client_list={};
            // 连接服务端
            function connect() {
               // 创建websocket
               ws = new WebSocket("ws://"+document.domain+":7272");
               // 当socket连接打开时，输入用户名
               ws.onopen = onopen;
               // 当有消息时根据消息类型显示不同信息
               ws.onmessage = onmessage; 
               ws.onclose = function() {
                  console.log("连接关闭，定时重连");
                  connect();
               };
               ws.onerror = function() {
                  console.log("出现错误");
               };
            }
            // 连接建立时发送登录信息
            function onopen()
            {
                // 登录
                @if($logged_in_user)
                var login_data = '{"type":"login","client_name":"{{$logged_in_user->user_name}}","room_id":"<?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1?>"}';
                ws.send(login_data);
                // console.log("websocket握手成功，发送登录数据:"+login_data);
                @endif
                
                
            }
            // 服务端发来消息时
            function onmessage(e)
            {
                // console.log(e.data);
                var data = JSON.parse(e.data);
                switch(data['type']){
                    // 服务端ping客户端
                    case 'ping':
                        ws.send('{"type":"pong"}');
                        break;;
                    // 登录 更新用户列表
                    case 'login':
                        //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
                        // say(data['client_id'], data['client_name'],  data['client_name']+' 加入了聊天室', data['time']);
                        //  更新 

                        @if(Active::checkUriPattern('im'))
                            if(data['client_list'])
                            {
                                client_list = data['client_list'];
                            }
                            else
                            {
                                client_list[data['client_id']] = data['client_name']; 
                            }
                            flush_client_list();
                            // console.log(data['client_name']+"登录成功");
                        @endif

                        break;
                    // 发言
                    case 'say':
                        //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                        say(data['from_client_id'], data['from_client_name'], data['content'], data['time']);
                        break;
                    // 用户退出 更新用户列表
                    case 'logout':
                        //{"type":"logout","client_id":xxx,"time":"xxx"}
                        // say(data['from_client_id'], data['from_client_name'], data['from_client_name']+' 退出了', data['time']);
                        @if(Active::checkUriPattern('im'))
                        delete client_list[data['from_client_id']];
                        flush_client_list();
                        @endif
                }
            }
            // 输入姓名
            function show_prompt(){  
                name = prompt('输入你的名字：', '');
                if(!name || name=='null'){  
                    name = '游客';
                }
            }  
            @if(Active::checkUriPattern('im'))
            // 提交对话
            function onSubmit() {
              var input = document.getElementById("textarea");
              var to_client_id = $("#client_list option:selected").attr("value");
              var to_client_name = $("#client_list option:selected").text();
              ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+input.value.replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')+'"}');
              input.value = "";
              input.focus();
            }
            // 刷新用户列表框

            function flush_client_list(){
                var userlist_window = $("#userlist");
                var client_list_slelect = $("#client_list");
                userlist_window.empty();
                client_list_slelect.empty();
                userlist_window.append('<h4>在线用户</h4><ul>');
                client_list_slelect.append('<option value="all" id="cli_all">所有人</option>');
                for(var p in client_list){
                    userlist_window.append('<li id="'+p+'">'+client_list[p]+'</li>');
                    client_list_slelect.append('<option value="'+p+'">'+client_list[p]+'</option>');
                }
                $("#client_list").val(select_client_id);
                userlist_window.append('</ul>');
            }
            @endif
            
            // 发言
            function say(from_client_id, from_client_name, content, time){
                //解析新浪微博图片
                content = content.replace(/(http|https):\/\/[\w]+.sinaimg.cn[\S]+(jpg|png|gif)/gi, function(img){
                    return "<a target='_blank' href='"+img+"'>"+"<img src='"+img+"'>"+"</a>";}
                );
                //解析url
                content = content.replace(/(http|https):\/\/[\S]+/gi, function(url){
                    if(url.indexOf(".sinaimg.cn/") < 0)
                        return "<a target='_blank' href='"+url+"'>"+url+"</a>";
                    else
                        return url;
                }
                );

                @if(Active::checkUriPattern('im'))
                $("#dialog").append('<div class="speech_item"> '+from_client_name+' <br> '+time+'<div style="clear:both;"></div><p class="triangle-isosceles top">'+content+'</p> </div>').parseEmotion();
                @else 
                swal('',content,'');
                @endif
            }

            connect();
        // });
          </script>
        <!-- end chat -->

        @include('includes.partials.ga')
    </body>
</html>