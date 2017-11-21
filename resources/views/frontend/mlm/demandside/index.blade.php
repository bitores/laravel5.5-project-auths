@extends('frontend.layouts.app')

@section('title', app_name() . ' | 需求方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">产品列表 <a href="{{route('frontend.mlm.demandside.product.create')}}" class="btn pull-right">新建产品</a><div class="btn pull-right">批量提交审核</div> </div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">

	        		<div class="box box-success">
	        			<div class="box-body">
				            <div class="table-responsive">
				                <table id="products-table" class="table table-condensed table-hover">
				                    <thead>
				                        <tr>

				                            <th>模型名称</th>
				                            <th>模型制作周期</th>
				                            <th>任务状态</th>
				                            <th>是否撤单（接单后不允许撤单）</th>
				                            <th>模型制作费用（单位/元）</th>

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
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    <script type="text/javascript" src="/js/libs/html2pdf2/jspdf.min.js"></script>
	<script type="text/javascript" src="/js/libs/html2pdf2/html2canvas.min.js"></script>
	<script type="text/javascript" src="/js/libs/html2pdf2/html2pdf.js"></script>
    <script>
        $(function() {
            $('#products-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("frontend.mlm.demandside.products.get") }}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'product_no', name: ''},
                    {data: 'cycle', name: ''},
                    {data: 'status_no', name: ''},
                    {data:'actions', name:''},
                    {data: 'fee', name: ''},
                ],
                // order: [[1, "asc"]]
            });

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


        });
    </script>
@endsection