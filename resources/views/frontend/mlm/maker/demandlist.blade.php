@extends('frontend.layouts.app')

@section('title', app_name() . ' | 制作方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">需求池<a href="{{route('frontend.mlm.producer.index')}}" class="btn pull-right">我的订单</a></div>
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
				                            <th>任务相关资料</th>
				                            <th>建模费用</th>
				                            <!-- <th>模型风格</th> -->
				                            <th>模型制作周期</th>
				                            <!-- <th>是否审核通过</th> -->
				                            <!-- <th>下载资料包</th> -->
				                            <th>是否接单</th>

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
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("frontend.mlm.producer.product.alltasks") }}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'product_no', name: ''},
                    {data: 'resource', name: ''},
                    {data: 'fee', name: ''},
                    // {data: 'style', name: ''},
                    {data: 'cycle', name: ''},
                    {data:'orders', name:''},
                    // {data: 'download', name: ''},
                ],
                // order: [[1, "asc"]]

                oLanguage: {
		            "sProcessing": "正在加载中......",
		            "sLengthMenu": "每页显示 _MENU_ 条记录",
		            "sZeroRecords": "对不起，查询不到相关数据！",
		            "sEmptyTable": "表中无数据存在！",
		            "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
		            "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
		            "sSearch": "搜索",
		            "oPaginate": {
		                "sFirst": "首页",
		                "sPrevious": "上一页",
		                "sNext": "下一页",
		                "sLast": "末页"
		            }
		        } //多语言配置
            });

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