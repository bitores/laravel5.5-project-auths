@extends('frontend.layouts.app')

@section('title', app_name() . ' | 制作方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading fix"><big>需求池</big><a href="{{route('frontend.mlm.producer.index')}}" class="btn pull-right">我的订单</a></div>
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
				                            <th>相关资料</th>
				                            <th>建模费用</th>
				                            <!-- <th>模型风格</th> -->
				                            <th>制作周期</th>
				                            <!-- <th>是否审核通过</th> -->
				                            <!-- <th>下载资料包</th> -->
				                            <th>接单</th>

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
	@include('frontend.includes.dataTableSetting')
    <script>
        $(function() {

    	$.dataTableSetting.ajax.url = '{{ route("frontend.mlm.producer.product.alltasks") }}';
		$.dataTableSetting.columns = [
		    {data: 'product_no', name: ''},
            {data: 'resource', name: ''},
            {data: 'fee', name: ''},
            // {data: 'style', name: ''},
            {data: 'cycle', name: ''},
            {data:'orders', name:''},
            // {data: 'download', name: ''},
		];

		$('#products-table').DataTable($.dataTableSetting);

            $("#products-table").on('click', '.orderbtn', function(){
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
				            url: '{{ route("frontend.mlm.producer.product.order") }}',
				            type:'POST',
				            data:{
				                'productid':proid
				            },
				            success: function(res) {
				                if(0 === res.code){
				                    swal("OK", "操作成功", "success");
									location.href="/producer";
				                } else {
				                    swal("OMG", "操作失败：" + res.msg, "error");
				                    location.reload();
				                }

				                
				            },
				            error: function(res) {
				                // swal.close()
				                swal("OMG", "操作失败:", "error");
				                location.reload();
				            }
				        });
		            }
		            

		            
		        });
        	});

        
        });
    </script>
@endsection