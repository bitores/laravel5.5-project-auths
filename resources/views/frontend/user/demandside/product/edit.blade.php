@extends('frontend.layouts.app')

@section('title', app_name() . ' | 编辑产品')

@section('after-styles')
    <link href="/css/libs/webuploader/webuploader.css" rel="stylesheet">
@endsection

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">编辑产品 <div class="btn pull-right">删除产品</div></div>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-7">
        		<h3>产品图片</h3>
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

                <div class="form-group">
                        <div class="col-md-12">
                            <label>CAD资料</label>
                            <div class="btn btn-block btn-default fileupload1" id="fileupload1">上传</div>
                            <span class="filelist1" id="filelist1" style="word-break: break-all;" file_id="{{$product->cad_id}}">
                                @if($product->cad_id)
                                <h4>已上传</h4>
                                @else
                                <h4>未上传</h4>
                                @endif
                            </span>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-12">
                            <label>其它资料</label>
                            <div class="btn btn-block btn-default fileupload2" id="fileupload2">上传</div>
                            <span class="filelist2" id="filelist2" style="word-break: break-all;" file_id="{{$product->file_id}}">
                                @if($product->file_id)
                                <h4>已上传</h4>
                                @else
                                <h4>未上传</h4>
                                @endif
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
                                    <div class="btn btn-block btn-info" id="createBrand">新建</div>
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
                    		{{ Form::textarea('mobile', $product->introduction, ['class' => 'form-control',  'id'=>'introduction', 'maxlength' => '100', 'required' => 'required', 'placeholder' => '产品简介（不超过100字）', 'style'=>'height:100px;']) }}
                    	</div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12" style="padding: 0">
                            <div class="col-xs-6">
                                <a href="{{route('frontend.user.demandside.index')}}" class="btn btn-block btn-danger">取消</a>
                            </div><!--col-md-6-->
                            <div class="col-xs-6">
                                <div class="btn btn-block btn-info" id="saveBtn">保存</div>
                            </div><!--col-md-6-->
                        </div>
                    </div><!--form-group-->

                    


                    <div class="form-group">
                        <div class="col-md-12">

                                {{ Form::submit('提交审核', ['class' => 'btn btn-block btn-success']) }}
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
{'path':"/uploads/materials/{{str_replace("\\",'/',$image->path)}}",'id':"{{$image->id}}"},
@endforeach
@endif
];
</script>

<script src="/js/libs/webuploader/webuploader.nolog.js"></script>
<script src="/js/libs/webuploader/webuploadRun.js"></script>
<script src="/js/libs/webuploader/webuploadImageRun.js"></script>
<script>

$("#categoryA").change(function(){
    $("#categoryB").val(-1);
    $("#categoryB option[value!=-1]").hide();
    var tag = 'categorya-' + $(this).val();
    $("#categoryB option[class="+tag+"]").show();
});
var $_currentProduct=null, saveBtn_handling = false;
$("#saveBtn").on('click', function(){
    if(saveBtn_handling==false){
        saveBtn_handling = true;

        var $lis = $('#uploader .state-complete');

        var ret = [];
        $lis.each(function(index, item){
            ret[index] = $(item).attr('file_id');
        });

        $.ajax({
             type: "POST",
             url: "{{route('frontend.user.demandside.product.save')}}",
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
                'images':ret.join(',')
            },
            dataType: "json",
            success: function(res){
                saveBtn_handling = false;
                $_currentProduct = res.data['product_id'];
                console.log($_currentProduct);
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
                url: "/demandside/brand/create",
                type:'POST',
                data:{
                    'brd_name':inputValue
                },
                success: function(res) {
                    if(0 === res.code){
                        $('#brand').append("<option value='"+res.data['id']+"' selected='selected'>"+res.data['brd_name']+"</option>")
                    }
                    // swal.close();
                    swal("操作成功", "创建成功", "success");
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