@extends('frontend.layouts.app')

@section('title', app_name() . ' | 产品信息')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">产品信息</div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
        		<h3>产品图片 <small> # 简要说明为何需要设置主图片（要求全部展示，不要局部照片）</small></h3>
        		
        			左边
        		<!-- </div> -->
        	</div>
        	<div class="col-md-4">
    			 {{ Form::open(['route' => 'frontend.auth.register.mobile.post', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品牌/厂家</label>
                            <div class="col-md-12">
                                品牌/厂家
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品型号/名字</label>
                    		<div class="col-md-12">
                                产品型号/名字      
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品类</label>
                            <div class="col-md-12">
                                一些品类信息
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>风格类别</label>
                            <div class="col-md-12">
                                一些风格类别信息
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>建模费用</label>
                    		<div class="col-md-12">
                                一些建模费用信息
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品简介</label>
                    		<div class="col-md-12">
                                一些产品简介信息
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>CAD资料</label>
                            <div class="col-md-12">
                                已上传
                            </div>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>其它资料</label>
                            <div class="col-md-12">
                                已上传
                            </div>
                        </div>
                    </div><!--form-group-->



                    <div class="form-group">
                        <div class="col-md-12" style="padding: 0">
                            @if(0==$status)
                            <!-- 未提交审核 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-danger">编辑</div>
                            </div>
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">提交审核</div>
                            </div>
                            @elseif(1==$status)
                            <!-- 审核未通过 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-danger">编辑</div>
                            </div>
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">查看审核结果</div>
                            </div>
                            @elseif(2==$status)
                            <!-- 审核通过 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">提交到需求池</div>
                            </div>
                            @elseif(3==$status)
                            <!-- 审核时 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">接单并下载资料包</div>
                            </div>
                            @endif
                        </div>
                    </div><!--form-group-->

                {{ Form::close() }}
    		</div>
    	</div>
    </div>
</div>
@endsection