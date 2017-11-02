@extends('frontend.layouts.app')

@section('title', app_name() . ' | 新建产品')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">新建产品</div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
        		<h3>产品图片 <small> # 简要说明为何需要设置主图片（要求全部展示，不要局部照片）</small></h3>
        		
        			左边
        		<!-- </div> -->
        	</div>
        	<div class="col-md-4">
    		<!-- <div class="panel panel-default"> -->
    			 {{ Form::open(['route' => 'frontend.auth.register.mobile.post', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品型号/名字</label>
                    		{{ Form::tel('mobile', null, ['class' => 'form-control', 'maxlength' => '11', 'required' => 'required', 'placeholder' => '产品型号/名字']) }}
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品类</label>
                            <select class="form-control select2 col-md-12" style="width: 100%;">
                                <option selected="selected">Alabama</option>
                                <option>Alaska</option>
                                <option>California</option>
                                <option>Delaware</option>
                                <option>Tennessee</option>
                                <option>Texas</option>
                                <option>Washington</option>
                            </select>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>风格类别</label>
                            <select class="form-control select2 col-md-12" style="width: 100%;">
                                <option selected="selected">极科主义</option>
                                <option>现时代简约</option>
                                <option>后现代</option>
                                <option>新中式</option>
                                <option>中式</option>
                                <option>美式</option>
                                <option>欧式</option>
                                <option>法式</option>
                                <option>美式田园</option>
                                <option>意大利</option>
                                <option>美式古典</option>
                                <option>北欧</option>
                                <option>英式</option>
                                <option>地中海</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>建模费用</label>
                    		{{ Form::text('mobile', null, ['class' => 'form-control', 'maxlength' => '10', 'required' => 'required', 'placeholder' => '建模费用(单位：元)']) }}
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品简介</label>
                    		{{ Form::textarea('mobile', null, ['class' => 'form-control', 'maxlength' => '100', 'required' => 'required', 'placeholder' => '产品简介（不超过100字）', 'style'=>'height:100px;']) }}
                    	</div>
                    </div><!--form-group-->


                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-sm-4">
                                {{ Form::submit('取消', ['class' => 'btn btn-block btn-warning']) }}
                            </div><!--col-md-6-->
                            <div class="col-sm-4">
                                {{ Form::submit('保存', ['class' => 'btn btn-block btn-info']) }}
                            </div><!--col-md-6-->
                            <div class="col-sm-4">
                                {{ Form::submit('提交审核', ['class' => 'btn btn-block btn-primary']) }}
                            </div><!--col-md-6-->
                        </div>
                    </div><!--form-group-->

                {{ Form::close() }}
    		</div>
    	</div>
    </div>
</div>
@endsection