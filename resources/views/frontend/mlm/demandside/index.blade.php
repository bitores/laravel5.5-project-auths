@extends('frontend.layouts.app')

@section('title', app_name() . ' | 需求方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading fix"><big>产品列表</big> <a href="{{route('frontend.mlm.demandside.product.create')}}" class="btn pull-right">新建产品</a><div class="btn pull-right" id="allsubmit">一键提交审核</div> </div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">

	        		<div class="box box-success">
	        			<div class="box-body">
				            <div class="table-responsive">
				                <table id="products-table" class="table table-condensed table-hover spaing-fix">
				                    <thead>
				                        <tr>

				                            <th>模型名称</th>
				                            <th>制作周期</th>
				                            <th>制作费用</th>
				                            <th>任务状态</th>
				                            <th>撤单</th>
				                            
				                        </tr>
				                    </thead>
				                </table>
				            </div><!--table-responsive-->
				        </div><!-- /.box-body -->

	        		</div>
	        		
	        	</div>	        	
	        </div>
	    </div>
	</div>
@endsection

@section('after-scripts')
{{ Html::script("/js/backend/plugin/dt-1.10.15/datatables.min.js") }}
{{ Html::script("/js/backend/plugin/datatables/dataTables-extend.js") }}
<script type="text/javascript" src="/js/libs/html2pdf2/jspdf.min.js"></script>
<script type="text/javascript" src="/js/libs/html2pdf2/html2canvas.min.js"></script>
<script type="text/javascript" src="/js/libs/html2pdf2/html2pdf.js"></script>
@include('frontend.includes.dataTableSetting')
<script>
$(function() {

	$.fn.dataTable.ext.search.push(  
	    function( settings, data, dataIndex ) {  
	         console.log(data);
	        return false;  
	    }  
	);

	$.dataTableSetting.ajax.url = '{{ route("frontend.mlm.demandside.products.get") }}';
	$.dataTableSetting.columns = [
        {data: 'product_no', name: ''},
        {data: 'cycle', name: ''},
        {data: 'fee', name: ''},
        {data: 'status_no', name: ''},
        {data:'actions', name:''},
        
    ];

    $('#products-table').DataTable($.dataTableSetting);

    $("#products-table").on('click', '.download', function(){
        var proid = $(this).attr('data-proid');
        console.log(proid);

        swal({
            title: "下载修改意见",
            text: "点击确认下载修改意见文档",
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
		            url: "/demandside/product/review",
		            type:'POST',
		            data:{
		                'productid':proid
		            },
		            success: function(res) {
		                if(0 === res.code){
		                    swal("OK", "操作成功", "success");
							html2pdf(res.data.comments, {
							  margin:       1,
							  filename:     '修改意见文档.pdf',
							  image:        { type: 'jpeg', quality: 0.98 },
							  html2canvas:  { dpi: 192, letterRendering: true },
							  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
							});
		                } else {
		                    swal("OMG", "操作失败", "error");
		                }
		            },
		            error: function(res) {
		                // swal.close()
		                swal("OMG", "操作失败", "error");
		            }
		        });
            }
            

            
        });
	});


	$("#products-table").on('click', '.downloadmodel', function(){
        var proid = $(this).attr('data-proid');
        console.log(proid);

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

	$("#products-table").on('click', '.cancelbtn', function(){
        var proid = $(this).attr('data-proid');
        console.log(proid);

        swal({
            title: "撤消产品任务",
            text: "点击确认撤消任务",
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
		            url: "{{route('frontend.mlm.demandside.product.canceltask')}}",
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

		                location.reload();
		            },
		            error: function(res) {
		                // swal.close()
		                swal("OMG", "操作失败", "error");
		                location.reload();
		            }
		        });

            }

        });
	});

	$("#products-table").on('click', '.postbtn',  function(){
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


	$("#allsubmit").on('click', function(){

        swal({
            title: "批量提交审核",
            text: "点击确认执行任务",
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
		            url: "{{route('frontend.mlm.demandside.product.batsubmit')}}",
		            type:'POST',
		            success: function(res) {
		                if(0 === res.code){
		                    swal("OK", "操作成功", "success");
		                } else {
		                    swal("OMG", "操作失败", "error");
		                }

		                location.reload();
		            },
		            error: function(res) {
		                // swal.close()
		                swal("OMG", "操作失败", "error");
		                location.reload();
		            }
		        });

            }
        });

	});



});
</script>
@endsection