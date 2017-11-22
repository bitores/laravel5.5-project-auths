@extends('frontend.layouts.app')

@section('title', app_name() . ' | 制作方主页')

@section('after-styles')
	<link href="/css/libs/webuploader/webuploader.css" rel="stylesheet">
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
    <style type="text/css">
    	div[id^='rt_rt_']{
    		width: 100%!important;
    		height: 100%!important;
    	}
    </style>
@endsection

@section('content')
<div class="modal fade" id="upload-dialog">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">
                  <sdivan class="close" data-dismiss="modal">&times;</sdivan>
                   模型上传
               </h4>
           </div>
           <div  id="editor" class="modal-body editor">
                <div class="btn btn-block btn-default fileupload" id="fileupload">选择文件</div>
                <span class="filelist" id="filelist" style="word-break: break-all;"></span>
           </div>
           <div class="modal-footer">
               <div id="save-model" class="btn btn-info pull-right save-model">提交审核</div>
           </div>
       </div>
   </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">我的订单<a href="{{route('frontend.mlm.producer.demandlist')}}" class="btn pull-right">需求池</a></div>
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
    <script type="text/javascript">var CSRF_TOKEN = "{{ csrf_token() }}";</script>
    <script type="text/javascript" src="/js/libs/html2pdf2/jspdf.min.js"></script>
	<script type="text/javascript" src="/js/libs/html2pdf2/html2canvas.min.js"></script>
	<script type="text/javascript" src="/js/libs/html2pdf2/html2pdf.js"></script>
	<script src="/js/libs/webuploader/webuploader.nolog.js"></script>
	<script src="/js/libs/webuploader/webuploadRun.js"></script>
    <script>
        $(function() {

        	new bindWebupload('#fileupload',$('#filelist'),'上传模型文档','MODEL');
        	$cur_uploadmodel = -1;
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
				            url: '{{ route("frontend.mlm.producer.product.cancelorder") }}',
				            type:'POST',
				            data:{
				                'productid':proid
				            },
				            success: function(res) {
				                if(0 === res.code){
				                    swal("OK", "操作成功", "success");
				                    location.reload();
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
				            url: "/producer/model/review",
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


            $("#products-table").on('click', '.uploadbtn', function(){
            	$cur_uploadmodel = $(this).attr('data-proid');
	            console.log($cur_uploadmodel);
	            $("#filelist").removeAttr('file_id');
	            $("#filelist").empty();
            });

        	$("#save-model").on('click', function(){
	            var model_id = $("#filelist").attr('file_id');

	            if(!!model_id == false) {
	            	swal('OMG','模型未上传','error');
	            	return;
	            }

	            $('#upload-dialog').modal('hide')

	            swal({
		            title: "提交审核",
		            text: "点击确认进行提交",
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
				            url: '{{ route("frontend.mlm.producer.product.model") }}',
				            type:'POST',
				            data:{
				                'productid':$cur_uploadmodel,
				                'modelid': model_id
				            },
				            success: function(res) {
				                if(0 === res.code){
				                    swal("OK", "操作成功", "success");
				                    location.reload();
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