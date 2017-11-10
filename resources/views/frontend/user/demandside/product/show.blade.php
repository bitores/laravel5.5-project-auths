@extends('frontend.layouts.app')

@section('title', app_name() . ' | 产品信息')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">产品信息</div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
        		<h3>产品图片</h3>
        			@foreach($images as $image)
                    <img style="width:150px;height: 150px" src="/uploads/materials/{{str_replace("\\",'/',$image->path)}}">
                    @endforeach
        		<!-- </div> -->
        	</div>
        	<div class="col-md-4">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品牌/厂家</label>
                            <div class="col-md-12">
                                {{$brand->name}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品型号/名字</label>
                    		<div class="col-md-12">
                                {{$product->product_no}}     
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品类</label>
                            <div class="col-md-12">
                                {{$product->a_id . '-' . $product->b_id}}
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>风格类别</label>
                            <div class="col-md-12">
                                {{$product->style_id}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>建模费用</label>
                    		<div class="col-md-12">
                                {{$product->fee}}
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品简介</label>
                    		<div class="col-md-12">
                                {{$product->introduction}}
                            </div>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>CAD资料</label>
                            <div class="col-md-12">
                                @if(is_null($product->cad_id))
                                    未上传
                                @else
                                    已上传
                                @endif
                            </div>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>其它资料</label>
                            <div class="col-md-12">
                                @if(is_null($product->file_id))
                                    未上传
                                @else
                                    已上传
                                @endif
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
                            <!-- 审核中 -->
                            <!-- <div class="col-xs-6">
                                <div class="btn btn-block btn-danger">编辑</div>
                            </div>
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">查看审核结果</div>
                            </div> -->
                            @elseif(2==$status)
                            <!-- 审核未通过 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-danger">编辑</div>
                            </div>
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">查看审核结果</div>
                            </div>
                            @elseif(3==$status)
                            <!-- 审核通过 -->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info">发布任务</div>
                            </div>
                            @endif
                        </div>
                    </div><!--form-group-->

    		</div>
    	</div>
    </div>
</div>
@endsection