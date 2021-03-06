@extends('frontend.layouts.app')

@section('title', app_name() . ' | 编辑产品')

@section('after-styles')
    <link href="/css/libs/webuploader/webuploader.css" rel="stylesheet">
@endsection

@section('content')

<div class="panel panel-default">
    <div class="panel-heading fix"><big>产品【{{$product->product_no}}】</big>
    @if($product->status_no < 1006)
    <div data-proid="{{$product->id}}" id="delbtn" class="btn pull-right">删除</div>
    @endif
    <!-- <div data-proid="{{$product->id}}" id="delbtn" class="btn">删除产品</div> -->
    <div class="btn pull-right" id="saveBtn">保存</div>
    </div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
        		<div class="form-group">
                    <div class="col-md-12">
                        <label>产品图片</label>
                        <div id="uploader">
                            <div class="queueList">
                                <div id="dndArea" class="placeholder">
                                    <div id="filePicker"></div>
                                    <p>最多上传20张</p>
                                </div>
                            </div>
                            <div class="statusBar" style="display:none;">
                                <div class="progress">
                                    <span class="text">0%</span>
                                    <span class="percentage"></span>
                                </div><div class="info"></div>
                                <div class="btns">
                                    <div id="filePicker2"></div><!-- <div class="uploadBtn">开始上传</div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12" style="margin-top: 50px;border-bottom: 1px solid #dadada;">
                        <label>CAD资料</label>
                        <div class="btn fileupload1 pull-right" id="fileupload1" style="text-align: right">选择上传</div>
                        <span class="filelist1" id="filelist1" style="word-break: break-all;" file_id="{{$product->cad_id}}">
                            {{$product->cad_id?'已上传':'未上传'}}
                        </span>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-12" style="margin-top: 50px;border-bottom: 1px solid #dadada;">
                        <label>其它资料</label>
                        <div class="btn fileupload2 pull-right" id="fileupload2" style="text-align: right">选择上传</div>
                        <span class="filelist2" id="filelist2" style="word-break: break-all;" file_id="{{$product->file_id}}">
                            {{$product->file_id?'已上传':'未上传'}}
                        </span>
                    </div>
                </div>
        	</div>
        	<div class="col-md-4">
    			 {{ Form::open(['route' => 'frontend.auth.register.mobile.post', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品牌/厂家</label>
                            <div class="col-md-12" style="padding-left: 0;padding-right: 0">
                                <div class="col-xs-8" style="padding-left: 0;padding-right: 0">
                                    <select class="form-control select2 col-md-12" id="brand">
                                        @foreach($brands as $brand)
                                        @if($product->brand_id==$brand->id)
                                        <option value="{{$brand->id}}" selected>{{$brand->name}}</option>
                                        @else
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <div class="btn btn-block btn-fix" id="createBrand">新建</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品型号/名字</label>
                    		{{ Form::text('pType', $product->product_no, ['class' => 'form-control', 'maxlength' => '50', 'id'=>'product_no', 'required' => 'required', 'placeholder' => '产品型号/名字']) }}
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>品类</label>
                            <div class="col-md-12" style="padding-left: 0; padding-right: 0">
                                <div class="col-xs-6" style="padding: 0">
                                    <select class="form-control select2 col-md-6" id="categoryA">
                                        <option selected="selected" disabled="disable" value="-1">请选择</option>
                                        @foreach($categories_a as $a)
                                        @if($product->a_id==$a->id)
                                        <option value="{{$a->id}}" selected>{{$a->name}}</option>
                                        @else
                                        <option value="{{$a->id}}">{{$a->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6" style="padding: 0">
                                    <select class="form-control select2 col-md-6" id="categoryB">
                                        <option selected="selected" disabled="disable" value="-1">请选择</option>
                                        @foreach($categories_b as $b)
                                        @if($product->b_id==$b->id)
                                        <option value="{{$b->id}}" class="categorya-{{$b->category_a_id}}" selected>{{$b->name}}</option>
                                        @elseif($product->a_id==$b->category_a_id)
                                        <option value="{{$b->id}}" class="categorya-{{$b->category_a_id}}">{{$b->name}}</option>
                                        @else
                                        <option value="{{$b->id}}" class="categorya-{{$b->category_a_id}}" hidden>{{$b->name}}</option>
                                        @endif
                                        @endforeach

                                    </select>
                                </div> 
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>风格类别</label>
                            <select class="form-control select2 col-md-12" style="width: 100%;" id="style">
                                <option selected="selected" disabled="disable" value="-1">请选择</option>
                                @foreach($styles as $style)
                                @if($product->style_id==$style->id)
                                <option value="{{$style->id}}" selected>{{$style->name}}</option>
                                @else
                                <option value="{{$style->id}}">{{$style->name}}</option>
                                @endif
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>建模费用</label>
                    		{{ Form::text('mobile', $product->fee, ['class' => 'form-control', 'id'=>'fee', 'maxlength' => '10', 'required' => 'required', 'placeholder' => '建模费用(单位：元)']) }}
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                    	<div class="col-md-12">
                            <label>产品简介</label>
                    		{{ Form::textarea('mobile', $product->introduction, ['class' => 'form-control',  'id'=>'introduction', 'maxlength' => '100', 'required' => 'required', 'placeholder' => '产品简介（不超过100字）', 'style'=>'height:100px;resize: none;']) }}
                    	</div>
                    </div><!--form-group-->


                    <div class="form-group">
                        <div class="col-md-12" style="text-align: center;">
                            <div id="submitBtn" class="btn btn-fix">提交审核</div>
                        </div>
                    </div><!--form-group-->

                {{ Form::close() }}
    		</div>
    	</div>
    </div>
</div>
@endsection


@section('after-scripts')
<script type="text/javascript">
var CSRF_TOKEN = $('input[name="_token"]').val();
var webupload_pickList=[
// {'path':"https://dim3d.xyz/uploads/materials/20171026/150899935276c708cf0155e8de.jpg",'id':1}
@if(isset($images))
@foreach($images as $image)
{'path':"/uploads/materials/{{str_replace("\\",'/',$image->path)}}",'id':"{{$image->id}}",'is_cover':"{{$image->is_cover}}"},
@endforeach
@endif
];
</script>

<script src="/js/libs/webuploader/webuploader.nolog.js"></script>
<script src="/js/libs/webuploader/webuploadRun.js"></script>
<script src="/js/libs/webuploader/webuploadImageRun.js"></script>
<script>
new bindWebupload('#fileupload1',$('#filelist1'),'上传CAD压缩包','CAD');
new bindWebupload('#fileupload2',$('#filelist2'),'上传其它资料压缩包','OTHER');
$("#delbtn").on('click', function(){
    // $(this).hide();
    var proid = $(this).attr('data-proid');

    swal({
        title: "删除此产品",
      text: "点击确认删除产品，或点击取消删除",
      type: "warning",
      showCancelButton:true,
      cancelButtonText:'取消',
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "确认",
      closeOnConfirm: true
    }, function(){

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
        
    });   

});
$("#categoryA").change(function(){
    $("#categoryB").val(-1);
    $("#categoryB option[value!=-1]").hide();
    var tag = 'categorya-' + $(this).val();
    $("#categoryB option[class="+tag+"]").show();
});
var $_currentProduct=null, saveBtn_handling = false;

$("#submitBtn").on('click', function(){
    // 本地验证 信息的完整性
    var $lis = $('#uploader .state-complete');
    var numReg = /^\d+(\.\d+)?$/;

    if($("#brand").val()==null){
        return swal("OMG", "品牌厂家未选择", "error"); 
    }

    if($("#product_no").val()==""){
        return swal("OMG", "产品名称未填写", "error"); 
    }

    if($("#categoryA").val()==null){
        return swal("OMG", "一级品类未选择", "error"); 
    }

    if($("#categoryB").val()==null){
        return swal("OMG", "二级品类未选择", "error"); 
    }

    if($("#style").val()==null){
        return swal("OMG", "风格类别未选择", "error"); 
    }

    if($("#fee").val()==""){
        return swal("OMG", "费用未填写", "error"); 
    } else if( !numReg.test($("#fee").val()) ){
        return swal("OMG", "费用中有非法字符", "error"); 
    }

    if($("#introduction").val()==""){
        return swal("OMG", "产品简介未填写", "error"); 
    }

    if($lis.length==0){
        return swal("OMG", "产品图片没有上传", "error"); 
    }

    var face_id = $('#uploader .success').parents('li').attr('file_id');

    if(!!face_id===false){
        return swal('OMG','请设置封面图','error');
    }

    // 服务端验证 信息的完整性
    var ret = [];
    $lis.each(function(index, item){
        ret[index] = $(item).attr('file_id');
    });
        
    $.ajax({
         type: "POST",
         url: "{{route('frontend.mlm.demandside.product.submit')}}",
         data: {
            'current_pro': "{{$product->id}}",
            'product_no':$("#product_no").val(),
            'style_id':$("#style").val(),
            'a_id':$("#categoryA").val(),
            'b_id':$("#categoryB").val(),
            'brand_id':$("#brand").val(),
            'cad_id':$("#filelist1").attr('file_id'),
            'file_id':$("#filelist2").attr('file_id'),
            'fee':$("#fee").val(),
            'introduction':$("#introduction").val(),
            'images':ret.join(','),
            'face_id': face_id
        },
        dataType: "json",
        success: function(res){
            saveBtn_handling = false;
            if(0 == res.code) {
                $_currentProduct = res.data['product_id'];
                console.log($_currentProduct);
                // swal("OK", "已提交审核", "success").then(function(){
                //     localtion.href="/products";
                // });

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

$("#saveBtn").on('click', function(){
    var numReg = /^\d+(\.\d+)?$/;
    if($("#fee").val()!="" && !numReg.test($("#fee").val()) ){
        return swal("OMG", "费用中有非法字符", "error"); 
    }

    if(saveBtn_handling==false){
        saveBtn_handling = true;

        var $lis = $('#uploader .state-complete');
        

        var ret = [];
        $lis.each(function(index, item){
            ret[index] = $(item).attr('file_id');
        });

        var face_id = $('#uploader .success').parents('li').attr('file_id');

        $.ajax({
             type: "POST",
             url: "{{route('frontend.mlm.demandside.product.save')}}",
             data: {
                'current_pro': "{{$product->id}}",
                'product_no':$("#product_no").val(),
                'style_id':$("#style").val(),
                'a_id':$("#categoryA").val(),
                'b_id':$("#categoryB").val(),
                'brand_id':$("#brand").val(),
                'cad_id':$("#filelist1").attr('file_id'),
                'file_id':$("#filelist2").attr('file_id'),
                'fee':$("#fee").val(),
                'introduction':$("#introduction").val(),
                'images':ret.join(','),
                'face_id': face_id
            },
            dataType: "json",
            success: function(res){
                saveBtn_handling = false;
                $_currentProduct = res.data['product_id'];
                console.log($_currentProduct);
                swal("OK", "内容成功保存", "success");
            },
            error: function(){
                saveBtn_handling = false;
            }
         });
    }
});

$('#createBrand').on('click', function(){
    swal({   
        title: "新建品牌", 
        type: 'input',  
        inputType: "text",   
        showCancelButton: true,   
        closeOnConfirm: false,   
        animation: "slide-from-top",   
        inputPlaceholder: "品牌/厂家",
    },function(inputValue){  

        if (inputValue) {
            $.ajax({
                url: "{{route('frontend.mlm.demandside.brand.create')}}",
                type:'POST',
                data:{
                    'brd_name':inputValue
                },
                success: function(res) {
                    if(0 === res.code){
                        $('#brand').append("<option value='"+res.data['id']+"' selected='selected'>"+res.data['brd_name']+"</option>")
                    }
                    // swal.close();
                    swal("OK", "品牌创建成功", "success");
                },
                error: function(res) {
                    // swal.close()
                    swal("OMG", "品牌已存在", "error");
                }
            });
            
        } 
            
    });
});
</script>

@endsection