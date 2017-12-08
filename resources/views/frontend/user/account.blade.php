@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="{{asset('/js/layui/css/layui.css')}}">
@endsection

@section('content')
    <div class="row">

        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading fix">
                <div role="presentation" style="display: inline-block;">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><big>{{ trans('navs.frontend.user.account') }}</big></a>
                </div>

                @if ($logged_in_user->canChangePassword())
                <div role="presentation" class="pull-right">
                    <a href="#password" aria-controls="password" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.change_password') }}</a>
                </div>
                @endif

                <div class="pull-right">
                    <a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.editor') }}</a>
                </div>
                @if(empty($logged_in_user->email))
                <div role="presentation" class="pull-right">
                    <a href="#email" aria-controls="email" role="tab" data-toggle="tab">绑定邮箱</a>
                </div>
                @endif

                @if(empty($logged_in_user->mobile))
                <div role="presentation" class="pull-right">
                    <a href="#phone" aria-controls="phone" role="tab" data-toggle="tab">绑定手机号</a>
                 </div>
                @endif

                </div>

                <div class="panel-body">

                    <div role="tabpanel">

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane mt-30 active" id="profile">
                                @include('frontend.user.account.tabs.profile')

                            </div><!--tab panel profile-->

                            <div role="tabpanel" class="tab-pane mt-30" id="edit">
                                @include('frontend.user.account.tabs.edit')
                            </div><!--tab panel profile-->

                            @if(empty($logged_in_user->email))
                            <div role="tabpanel" class="tab-pane mt-30" id="email">
                                @include('frontend.user.account.tabs.email')
                            </div><!--tab panel profile-->
                            @endif

                            @if(empty($logged_in_user->mobile))
                            <div role="tabpanel" class="tab-pane mt-30" id="phone">
                                @include('frontend.user.account.tabs.phone')
                            </div><!--tab panel profile-->
                            @endif

                            @if ($logged_in_user->canChangePassword())
                                <div role="tabpanel" class="tab-pane mt-30" id="password">
                                    @include('frontend.user.account.tabs.change-password')
                                </div><!--tab panel change password-->
                            @endif

                        </div><!--tab content-->

                    </div><!--tab panel-->

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-xs-12 -->

    </div><!-- row -->
@endsection