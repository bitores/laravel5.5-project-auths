{{ Form::open(['route' => 'frontend.user.mobile.bind', 'class' => 'form-horizontal']) }}

<div class="form-group">
    {{ Form::label('mobile', trans('validation.attributes.frontend.mobile'), ['class' => 'col-md-4 control-label']) }}
    <div class="col-md-6">
        {{ Form::tel('mobile', null, ['class' => 'form-control', 'maxlength' => '11', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.mobile')]) }}
    </div><!--col-md-6-->
</div><!--form-group-->

<div class="form-group">
    {{ Form::label('verifyCode', '验证码', ['class' => 'col-md-4 control-label']) }}
    <div class="col-md-6">
        <div style="display: inline-block; width: 50%;">
            {{ Form::tel('verifyCode', null, ['class' => 'form-control', 'maxlength' => '6', 'required' => 'required', 'placeholder' => '验证码']) }}
        </div><div style="display: inline-block; width: 40%;margin-left: 10%;">
            <div id="sendVerifySmsButton" class="btn btn-block btn-default">验证码</div>
        </div><!--col-md-3-->
    </div>
    
</div><!--form-group-->

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {{ Form::submit('绑定', ['class' => 'btn btn-primary']) }}
    </div><!--col-md-6-->
</div><!--form-group-->

{{ Form::close() }}

@section('after-scripts')
    @if (config('access.captcha.registration'))
        {!! Captcha::script() !!}
    @endif
    <script type="text/javascript" src="/js/libs/toplan/laravel-sms.js"></script>
    <script>
    $('#sendVerifySmsButton').sms({
        //laravel csrf token
        token       : "{{csrf_token()}}",
        //请求间隔时间
        interval    : 60,
        //请求参数
        requestData : {
            //手机号
            mobile : function () {
                return $('input[name=mobile]').val();
            },
            //手机号的检测规则
            mobile_rule : 'mobile_required'
        },

        // 消息展示方式（默认为 alert）
        notify: function(msg, type){
            alert(msg);
        }
    });
    </script>
@endsection