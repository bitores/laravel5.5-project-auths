@extends('frontend.layouts.app')

@section('title', app_name() . ' | 新建产品')

@section('content')
<div class="row">
	<div class="col-md-7">
		<h3>产品图片 <small>*简要说明为何需要设置主图片（要求全部展示，不要局部照片）</small></h3>
		<div class="panel panel-default">
			左边
		</div>
	</div>
	<div class="col-md-4 col-md-offset-1">
		<div class="panel panel-default">
			 {{ Form::open(['route' => 'frontend.auth.register.mobile.post', 'class' => 'form-horizontal']) }}

                <div class="form-group">
                	<div class="col-md-12">
                		{{ Form::tel('mobile', null, ['class' => 'form-control', 'maxlength' => '11', 'required' => 'required', 'placeholder' => '产品型号/名字']) }}
                	</div>
                </div><!--form-group-->

                <div class="form-group">
                	<div class="col-md-12">
                		{{ Form::tel('mobile', null, ['class' => 'form-control', 'maxlength' => '11', 'required' => 'required', 'placeholder' => '建模费用']) }}
                	</div>
                </div><!--form-group-->

                <div class="form-group">
                	<div class="col-md-12">
                		{{ Form::tel('mobile', null, ['class' => 'form-control', 'maxlength' => '11', 'required' => 'required', 'placeholder' => '产品简介（不超过100字）']) }}
                	</div>
                </div><!--form-group-->


                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        {{ Form::submit(trans('labels.frontend.auth.register_button'), ['class' => 'btn btn-primary']) }}
                    </div><!--col-md-6-->
                </div><!--form-group-->

            {{ Form::close() }}
		</div>
	</div>
</div>
@endsection