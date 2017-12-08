@extends('frontend.layouts.app')

@section('title', app_name() . ' | 产品信息')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading fix"><big>产品信息</big>
        @role('demandside')
        <!-- <a href="{{route('frontend.mlm.demandside.index')}}" class="btn pull-right">所有产品</a> -->
        @if($product->status_no < 1006)
            <div data-proid="{{$product->id}}" id="delbtn" class="btn pull-right">删除</div>
            @if(1000==$product->status_no||1002==$product->status_no||1003==$product->status_no)
            <a href="{{route('frontend.mlm.demandside.product.edit', $product->id)}}" class="btn pull-right">编辑</a>
            @endif
        @endif

        @endauth

    </div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
                <div class="form-group">
                    <div class="col-md-12" style="border-bottom: 1px solid #dadada;">
                        <label>产品图片</label>
                        <div class="col-md-12">
                            @if(count($images) == 0)
                            未上传
                            @else
                            @foreach($images as $image)
                            <img style="width:150px;height: 150px;margin: 10px;" src="/uploads/materials/{{str_replace("\\",'/',$image->path)}}">
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div><!--form-group-->

                <div class="form-group">
                    <div class="col-md-12" style="margin-top: 50px;border-bottom: 1px solid #dadada;">
                        <label>CAD资料</label>
                        <div class="pull-right">
                            {{$product->cad_path?'已上传':'未上传'}}
                        </div>
                    </div>
                </div><!--form-group-->

                <div class="form-group">
                    <div class="col-md-12" style="margin-top: 50px;border-bottom: 1px solid #dadada;">
                        <label>其它资料</label>
                        <div class="pull-right">
                            {{$product->file_path?'已上传':'未上传'}}
                        </div>
                    </div>
                </div><!--form-group-->
        		<!-- </div> -->
        	</div>
        	<div class="col-md-4">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品牌/厂家：</label>
                            <span>
                                {{$product->brand_name?:'-'}}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12" style="margin-top: 20px;">
                            <label>产品型号/名字：</label>
                    		<span>
                                {{$product->product_no?:'-'}}     
                            </span>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <label>品类：</label>
                            <span>
                                {{($product->ca_name?:'-') . ':' . ($product->cb_name?:'-')}}
                            </span>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-12" style="margin-top: 20px;">
                            <label>风格类别：</label>
                            <span>
                                {{$product->style_name?:'-'}}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12" style="margin-top: 20px;">
                            <label>建模费用：</label>
                    		<span>
                                {{$product->fee?('￥'.$product->fee):'-'}}
                            </span>
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                    	<div class="col-md-12" style="margin-top: 20px;">
                            <label>产品简介</label>
                    		<div class="col-md-12">
                                {{$product->introduction?:'-'}}
                            </div>
                    	</div>
                    </div><!--form-group-->


                    <div class="form-group">
                        <div class="col-md-12" style="padding: 20px 15px">

                            <div data-proid="{{$product->id}}" class="btn btn-fix download" id="download">资料包</div>

                            @if(1000==$product->status_no)
                            <!-- 未提交 -->
                            <div id="submitBtn" class="btn btn-fix" style="margin-left: 10px;">提交审核</div>
                            @elseif(1001==$product->status_no || 1006==$product->status_no)
                            <!-- 需求审核中 --><!-- 模型审核未通过 -->
                            @elseif(1002==$product->status_no)
                            <!-- 审核未通过 -->
                            <a href="{{route('frontend.mlm.demandside.product.assessment', $product->id)}}" class="btn btn-fix" style="margin-left: 10px;">审核结果</a>
                            @elseif(1003==$product->status_no)
                            <!-- 审核通过 -->
                            <div data-proid="{{$product->id}}" class="btn btn-fix" id="postbtn">发布任务</div>
                            @elseif(1005==$product->status_no)
                            <!-- 审核中 -->
                                @role('producerside')
                                    <div data-proid="{{$product->id}}" class="btn btn-fix" id="orderdownload" style="margin-left: 10px;">接单并下载资料包</div>
                                @endauth

                            @elseif($product->status_no>1006)
                            <!-- 模型审核未通过 -->
                                <div data-proid="{{$product->id}}" class="btn btn-fix downloadmodel" style="margin-left: 10px;" id="downloadmodel">模型</div>
                                @if(1008==$product->status_no)
                                <!-- 模型审核未通过 -->
                                <a href="{{route('frontend.mlm.producer.product.assessment', $product->id)}}" class="btn btn-fix" style="margin-left: 10px;">审核结果</a>
                                @endif
                            @endif
                        </div>
                    </div><!--form-group-->

    		</div>
    	</div>
    </div>
</div>
@endsection

@section("after-scripts")
<script type="text/javascript">
$("#postbtn").on('click', function(){
    var proid = $(this).attr('data-proid');

    swal({
        title: "发布此产品的任务",
        text: "点击确认则发布任务",
        type: "warning",
        showCancelButton:true,
        cancelButtonText:'取消',
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确认",
        closeOnConfirm: true
    }, function(){

        $.ajax({
            url: "{{route('frontend.mlm.demandside.product.posttask')}}",
            type:'POST',
            data:{
                'productid':proid
            },
            success: function(res) {
                if(0 === res.code){
                    swal("OK", "操作成功", "success");
                } else {
                    swal("OMG", "操作失败", "error");
                }
                location.href="/products";
            },
            error: function(res) {
                // swal.close()
                swal("OMG", "操作失败", "error");
                location.href="/products";
            }
        });
        
    });   

});

@role('demandside')
$("#delbtn").on('click', function(){
    // $(this).hide();
    var proid = $(this).attr('data-proid');

    swal({
        title: "删除此产品",
      text: "点击确认则删除产品或点击取消",
      type: "warning",
      showCancelButton:true,
      cancelButtonText:'取消',
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "确认",
      closeOnConfirm: true
    }, function(confirm){
        if(true == confirm) {
            $.ajax({
                url: "{{route('frontend.mlm.demandside.product.del')}}",
                type:'POST',
                data:{
                    'productid':proid
                },
                success: function(res) {
                    if(0 === res.code){
                        swal("OK", "操作成功", "success");
                    } else {
                        swal("OMG", "操作失败", "error");
                    }
                    location.href="/products";
                },
                error: function(res) {
                    // swal.close()
                    swal("OMG", "操作失败", "error");
                    location.href="/products";
                }
            });
        }        
    });   

});
@endauth



$("#submitBtn").on('click', function(){
    // 本地验证 信息的完整性
    if("{{$product->brand_name}}"==""){
        return swal("OMG", "品牌厂家未选择", "error"); 
    }

    if("{{$product->product_no}}"==""){
        return swal("OMG", "产品名称未填写", "error"); 
    }

    if("{{$product->ca_name}}"==""){
        return swal("OMG", "一级品类未选择", "error"); 
    }

    if("{{$product->cb_name}}"==""){
        return swal("OMG", "二级品类未选择", "error"); 
    }

    if("{{$product->style_name}}"==""){
        return swal("OMG", "风格类别未选择", "error"); 
    }

    if("{{$product->fee}}"==""){
        return swal("OMG", "费用未填写", "error"); 
    }

    if("{{$product->introduction}}"==""){
        return swal("OMG", "产品简介未填写", "error"); 
    }

    if({{count($images)}}==0){
        return swal("OMG", "产品图片没有上传", "error"); 
    }


    // 服务端验证 信息的完整性
        
    $.ajax({
         type: "POST",
         url: "{{route('frontend.mlm.demandside.product.oncesubmit')}}",
         data: {
            'current_pro': "{{$product->id}}",
        },
        dataType: "json",
        success: function(res){
            saveBtn_handling = false;
            if(0 == res.code) {
                $_currentProduct = res.data['product_id'];
                console.log($_currentProduct);
                swal({
                    title: "OK",
                      text: "已提交审核,点击跳转",
                      type: "success",
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "确认",
                      closeOnConfirm: false
                }, function(){
                    location.href="/products";
                });
            } else {
                swal("OMG", "信息不完整", "error");
            }
            
        },
        error: function(){
            saveBtn_handling = false;
        }
     });
});


$("#download").on('click', function(){
    var proid = $(this).attr('data-proid');
    swal({
        title: "确认下载产品资料包",
        text: "点击确认下载",
        type: "warning",
        cancelButtonText:'取消',
        confirmButtonText:'确认',
        showCancelButton: true,
        closeOnConfirm: false
    }, function (cycle) {
        console.log(cycle);
        if(false === cycle) {
            return 0;
        } 
        $.ajax({
            url: "/product/download",
            type:'POST',
            data:{
                'productid':proid
            },
            success: function(res) {
                if(0 === res.code){
                    location.href = res.data;
                    swal("OK", "操作成功", "success");
                } else {
                    swal("OMG", "操作失败", "error");
                }
            },
            error: function(res) {
                swal("OMG", "操作失败", "error");
            }
        });
    });
});

@if($product->status_no > 1006)
$("#downloadmodel").on('click', function(){
    var proid = $(this).attr('data-proid');
    
    swal({
        title: "确认下载模型",
        text: "点击确认下载",
        type: "warning",
        cancelButtonText:'取消',
        confirmButtonText:'确认',
        showCancelButton: true,
        closeOnConfirm: false
    }, function (cycle) {
        console.log(cycle);
        if(false === cycle) {
            return 0;
        } 
        $.ajax({
            url: "/product/downloadmodel",
            type:'POST',
            data:{
                'productid':proid
            },
            success: function(res) {
                if(0 === res.code){
                    location.href = res.data;
                    swal("OK", "操作成功", "success");
                } else {
                    swal("OMG", "操作失败", "error");
                }
                // location.reload();
            },
            error: function(res) {
                // swal.close()
                swal("OMG", "操作失败", "error");
                // location.reload();
            }
        });
       
    });


});
@endif

@if(1005==$product->status_no)
$("#orderdownload").on('click',function(){
    var proid = $(this).attr('data-proid');
    console.log(proid);

    swal({
        title: "接单",
        text: "点击确认进行接单",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: true,
        cancelButtonText:'取消',
        confirmButtonText:'确认',
        closeOnConfirm: true
    }, function (cycle) {
        console.log(cycle);
        if(true === cycle) {
            $.ajax({
                url: "{{route('frontend.mlm.producer.product.order')}}",
                type:'POST',
                data:{
                    'productid':proid
                },
                success: function(res) {
                    if(0 === res.code){
                        swal("OK", "接单成功成功", "success");

                        swal({
                            title: "接单成功",
                            text: "点击确认下载",
                            type: "warning",
                            cancelButtonText:'取消',
                            confirmButtonText:'确认',
                            showCancelButton: true,
                            closeOnConfirm: false
                        }, function (cycle) {
                            console.log(cycle);
                            if(false === cycle) {
                                return 0;
                            } 
                                $.ajax({
                                    url: "/product/download",
                                    type:'POST',
                                    data:{
                                        'productid':proid
                                    },
                                    success: function(res) {
                                        if(0 === res.code){
                                            location.href = res.data;
                                            swal("OK", "操作成功", "success");
                                        } else {
                                            swal("OMG", "操作失败", "error");
                                        }
                                        // location.reload();
                                    },
                                    error: function(res) {
                                        // swal.close()
                                        swal("OMG", "操作失败", "error");
                                        // location.reload();
                                    }
                                });
                           
                        });



                    } else {
                        swal("OMG", "操作失败：" + res.msg, "error");
                    }
                },
                error: function(res) {
                    // swal.close()
                    swal("OMG", "操作失败:", "error");
                }
            });
        }
        
    });
});
@endif
</script>
@endsection