@extends('frontend.layouts.app')

@section('title', app_name() . ' | 模型审核')

@section('after-styles')
    @include('vendor.ueditor.assets')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
    <style>
    .alert-editor{
        width: 100%;
    }
    </style>
@endsection

@section('content')
<div class="modal fade" id="alert-editor">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title">
                  <sdivan class="close" data-dismiss="modal">&times;</sdivan>
                   模型修改意见
               </h4>
           </div>
           <div  id="editor" class="modal-body editor"></div>
           <div class="modal-footer">
               <div id="save-content" class="btn btn-info pull-right save-content">提交</div>
           </div>
       </div>
   </div>

</div>
	<div class="panel panel-default">
	    <div class="panel-heading fix"><big>模型列表</big><a href="{{route('frontend.mlm.auditor.demandlist')}}" class="btn pull-right">需求列表</a></div>
<!--         <div id="alert-editor" class="alert-editor" hidden="">
            <div id="editor" class="editor"></div>
            <div id="save-content" class="btn btn-info pull-right save-content">提交修改意见</div>
        </div>
 -->
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
				                            <th>截止时间</th>
				                            <th>模型风格</th>
				                            <th>制作周期</th>
				                            <th>审核</th>
				                            <th>下载</th>
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
    @include('frontend.includes.ueditor')
    @include('frontend.includes.dataTableSetting')
    <script>
    $(function() {
	$.dataTableSetting.ajax.url = '{{ route("frontend.mlm.auditor.model.list") }}';
	$.dataTableSetting.columns = [
	    {data: 'product_no', name: ''},
	    {data: 'resource', name: ''},
	    {data: 'fee', name: ''},
	    {data: 'style', name: ''},
	    {data: 'cycle', name: ''},
	    {data:'actions', name:''},
	    {data: 'download', name: ''},
	];

	$('#products-table').DataTable($.dataTableSetting);


        $("#products-table").on('click', '.nopass', function(){
            ue.proid = $(this).attr('data-proid');
            console.log(ue.proid);
            $("#alert-editor").show();
           
        });

        $("#save-content").on('click', function(){
            var content= ue.getContent();
            // 

            // console.log(content);
            // return;
            $("#alert-editor").hide();
            $.ajax({
                url: '{{ route("frontend.mlm.auditor.model.nopass") }}',
                type:'POST',
                data:{
                    'productid':ue.proid,
                    'content':content
                },
                success: function(res) {
                    if(0 === res.code){
                        swal({
		                    title: "OK",
							text: "操作成功,点击跳转",
							type: "success",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "确认",
							closeOnConfirm: true
		                }, function(){
		                    location.reload();
		                });

                    } else {
                     	swal("OMG", "操作失败", "error");
                     	location.reload();
                    }
                    
                },
                error: function(res) {
                    // swal.close()
                    swal("OMG", "操作失败", "error");
                    location.reload();
                }
            });
        });

        $("#products-table").on('click', '.pass', function(){
            var proid = $(this).attr('data-proid');
            console.log(proid);

            swal({
	            title: "模型通过审核",
	            text: "点击确认提交",
	            type: "warning",
	            cancelButtonText:'取消',
	            confirmButtonText:'确认',
	            showCancelButton: true,
	            closeOnConfirm: false
	        }, function (cycle) {
	            console.log(cycle);
	            if(true == cycle) {
	            	$.ajax({
		                url: '{{ route("frontend.mlm.auditor.model.pass") }}',
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


        $("#products-table").on('click', '.download', function(){
            var proid = $(this).attr('data-proid');
            console.log(proid);

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
    });
</script>
                    
@endsection