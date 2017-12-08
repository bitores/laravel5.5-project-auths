<div class="row">
  
  <div class="col-sm-8">
      <table class="table table-hover ">

          <tr>
              <th>{{ trans('labels.frontend.user.profile.nickname') }}</th>
              <td>{{ $logged_in_user->nickname }}</td>
          </tr>
          <tr>
              <th>{{ trans('labels.frontend.user.profile.user_name') }}</th>
              <td>{{ $logged_in_user->user_name }}</td>
          </tr>
          <tr>
              <th>{{ trans('labels.frontend.user.profile.mobile') }}</th>
              <td>{{ $logged_in_user->mobile }}</td>
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
  </div>  
  <div class="col-sm-4">
            <div class="form">
                <div class="layui-upload" style="width:80%;margin:0 auto;padding-bottom:20px;">
                  <div class="layui-upload-list">
                    <img class="layui-upload-img" src="{{ $logged_in_user->picture }}"  onerror="this.src='/img/avatars/default.jpg'" style="width:150px;height:150px;border-radius:150px;">
                  </div>

                  <button class="layui-btn" style="width: 150px;background-color: transparent;color: #c6b99d" id="avatar">更换头像</button>
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
                  ,auto: true //
                  ,bindAction: '#upload' //
                  ,accept:'images'
                  ,exts:'jpg|png|gif|jpeg'
                  ,size:500
                  ,choose: function(obj){
                    var files = obj.pushFile();
                    
                    obj.preview(function(index, file, result){

                      $('.layui-upload-img').attr('src', result);


                      // console.log()

                      // $("#upload").show();
                      
                      //obj.upload(index, file); //
                      // delete files[index]; //
                    });
                  }
                ,done: function(response){
                    layer.msg(response.msg);
                    $("#upload").hide();
                    location.reload();
                }
                ,error: function(response){
                    // layer.msg(response);
                }
                });      

            });
            </script>

  </div>  
</div>




