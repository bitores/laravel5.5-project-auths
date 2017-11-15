@extends('frontend.layouts.app')

@section('title', app_name() . ' | 制作方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">需求池</div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">

	        		<div class="box box-success">
	        			<div class="box-body">
				            <div class="table-responsive">
				                <table id="products-table" class="table table-condensed table-hover">
				                    <thead>
				                        <tr>

				                        	<th>任务完成状态</th>
				                            <th>任务名称</th>
				                            <th>预览任务相关资料</th>
				                            <th>建模时间</th>
				                            <th>建模费用</th>
				                            <th>上传模型</th>
				                            <th>项目状态</th>
				                            <th>是否取消接单</th>

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
                    url: '{{ route("frontend.mlm.producer.product.tasks") }}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                	{data: 'product_finish', name:''},
                    {data: 'product_no', name: ''},
                    {data: 'resource', name: ''},
                    {data: 'cycle', name: ''},
                    {data: 'fee', name: ''},
                    {data: 'uploadbtn', name: ''},
                    {data: 'product_status', name: ''},
                    {data:'orders', name:''},
                    // {data: 'download', name: ''},
                ],
                // order: [[1, "asc"]]
            });

            $("#products-table").on('click', '.cancelbtn', function(){
	            var proid = $(this).attr('data-proid');
	            console.log(proid);

	            swal({
		            title: "取消接单",
		            text: "点击确认进行取消接单",
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
				            url: "/producer/product/cancelorder",
				            type:'POST',
				            data:{
				                'productid':proid
				            },
				            success: function(res) {
				                if(0 === res.code){
				                    swal("OK", "操作成功", "success");
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

        
        });
    </script>
@endsection