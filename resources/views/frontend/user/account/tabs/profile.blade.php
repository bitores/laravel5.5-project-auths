<table class="table table-striped table-hover">
    <tr>
        <th>{{ trans('labels.frontend.user.profile.avatar') }}</th>
        <td>
            <!-- <img src="{{ $logged_in_user->picture }}" class="user-profile-image" /> -->
            <div class="form">
                <div class="layui-upload" style="width:80%;margin:0 auto;padding-bottom:20px;">
                  <div class="layui-upload-list">
                    <img class="layui-upload-img" src="{{ $logged_in_user->picture }}" id="avatar" style="width:150px;height:150px;">
                  </div>

                  <button class="layui-btn" id="upload" style="display: none;">上传头像</button>
                </div> 
            </div>
            <script src="{{ asset('/js/layui/layui.js') }}"></script>
            <script>
            layui.use(['element','jquery','upload'], function(){
              var element = layui.element
                        $ = layui.jquery
                  upload  = layui.upload;
                
              upload.render({
                  elem: '#avatar'
                  ,url: '/uploadAvatar'
                  ,method:'post'
                  ,auto: false //Ñ¡ÔñÎÄ¼þºó²»×Ô¶¯ÉÏ´«
                  ,bindAction: '#upload' //Ö¸ÏòÒ»¸ö°´Å¥´¥·¢ÉÏ´«
                  ,accept:'images'
                  ,exts:'jpg|png|gif|jpeg'
                  ,size:500
                  ,choose: function(obj){
                    //½«Ã¿´ÎÑ¡ÔñµÄÎÄ¼þ×·¼Óµ½ÎÄ¼þ¶ÓÁÐ
                    var files = obj.pushFile();
                    
                    //Ô¤¶Á±¾µØÎÄ¼þ£¬Èç¹ûÊÇ¶àÎÄ¼þ£¬Ôò»á±éÀú¡£(²»Ö§³Öie8/9)
                    obj.preview(function(index, file, result){
                      // console.log(index); //µÃµ½ÎÄ¼þË÷Òý
                      // console.log(file); //µÃµ½ÎÄ¼þ¶ÔÏó
                      // console.log(result); //µÃµ½ÎÄ¼þbase64±àÂë£¬±ÈÈçÍ¼Æ¬
                      $('.layui-upload-img').attr('src', result);

                      $("#upload").show();
                      //ÕâÀï»¹¿ÉÒÔ×öÒ»Ð© append ÎÄ¼þÁÐ±í DOM µÄ²Ù×÷
                      
                      //obj.upload(index, file); //¶ÔÉÏ´«Ê§°ÜµÄµ¥¸öÎÄ¼þÖØÐÂÉÏ´«£¬Ò»°ãÔÚÄ³¸öÊÂ¼þÖÐÊ¹ÓÃ
                      // delete files[index]; //É¾³ýÁÐ±íÖÐ¶ÔÓ¦µÄÎÄ¼þ£¬Ò»°ãÔÚÄ³¸öÊÂ¼þÖÐÊ¹ÓÃ
                    });
                  }
                ,done: function(response){
                    layer.msg(response.msg);
                    //ÉÏ´«³É¹¦
                    $("#upload").hide();
                }
                ,error: function(response){
                    // layer.msg(response);
                }
                });      

            });
            </script>

        </td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.name') }}</th>
        <td>{{ $logged_in_user->name }}</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.email') }}</th>
        <td>{{ $logged_in_user->email }}</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.created_at') }}</th>
        <td>{{ $logged_in_user->created_at }} ({{ $logged_in_user->created_at->diffForHumans() }})</td>
    </tr>
    <tr>
        <th>{{ trans('labels.frontend.user.profile.last_updated') }}</th>
        <td>{{ $logged_in_user->updated_at }} ({{ $logged_in_user->updated_at->diffForHumans() }})</td>
    </tr>
</table>