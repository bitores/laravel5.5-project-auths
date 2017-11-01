@extends('frontend.layouts.app')

@section('title', app_name() . ' | Reset Password')

@section('content')
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="panel panel-default">

                <!-- <div class="panel-heading">{{ trans('labels.frontend.passwords.reset_password_box_title') }}</div> -->

                <div class="panel-body">

                    <div role="tabpanel">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab-mobile" aria-controls="profile" role="tab" data-toggle="tab">重置手机号密码</a>
                            </li>

                            <li role="presentation">
                                <a href="#tab-email" aria-controls="edit" role="tab" data-toggle="tab">重置邮箱密码</a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane mt-30 active" id="tab-mobile">
                                {{ Form::open(['route' => 'frontend.auth.password.reset.phone', 'class' => 'form-horizontal']) }}

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
                                    {{ Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-md-6">
                                        {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                                    </div><!--col-md-6-->
                                </div><!--form-group-->

                                <div class="form-group">
                                    {{ Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-md-6">
                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) }}
                                    </div><!--col-md-6-->
                                </div><!--form-group-->

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        {{ Form::submit(trans('labels.frontend.passwords.reset_password_button'), ['class' => 'btn btn-primary']) }}
                                    </div><!--col-md-6-->
                                </div><!--form-group-->

                                {{ Form::close() }}
                            </div><!--tab panel profile-->

                            <div role="tabpanel" class="tab-pane mt-30" id="tab-email">
                                {{ Form::open(['route' => 'frontend.auth.password.email.post', 'class' => 'form-horizontal']) }}

                                <div class="form-group">
                                    {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) }}
                                    <div class="col-md-6">
                                        {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                                    </div><!--col-md-6-->
                                </div><!--form-group-->

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        {{ Form::submit(trans('labels.frontend.passwords.send_password_reset_link_button'), ['class' => 'btn btn-primary']) }}
                                    </div><!--col-md-6-->
                                </div><!--form-group-->

                                {{ Form::close() }}
                            </div><!--tab panel profile-->

                        </div><!--tab content-->

                    </div><!--tab panel-->

                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->
@endsection
@section('after-scripts')
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