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
	    <div class="panel-heading">模型审核任务列表<a href="{{route('frontend.mlm.auditor.demandlist')}}" class="btn pull-right">需求审核列表</a></div>
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
				                <table id="products-table" class="table table-condensed table-hover">
				                    <thead>
				                        <tr>
				                            <th>模型名称</th>
				                            <th>任务相关资料</th>
				                            <th>截止时间</th>
				                            <th>模型风格</th>
				                            <th>模型制作周期</th>
				                            <th>是否审核通过</th>
				                            <th>下载模型和资料包</th>
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

    <script>
    $(function() {

        var toolbars = [["fullscreen","source","undo","redo","insertunorderedlist",  
        "insertorderedlist","cleardoc","selectall","searchreplace","preview","justifyleft","justifycenter","justifyright","justifyjustify",  
        "touppercase","tolowercase","indent","removeformat","formatmatch","autotypeset","customstyle","paragraph","rowspacingbottom","rowspacingtop","lineheight","fontsize","charts"]];

            var ue = UE.getEditor('editor',{    
            toolbars: toolbars,
            autoWidth: true,
            initialFrameWidth: '100%'
            });
            ue.ready(function() {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });



            $('#products-table').DataTable({
                dom: 'lfrtip',
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("frontend.mlm.auditor.model.list") }}',
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
                    {data: 'style', name: ''},
                    {data: 'cycle', name: ''},
                    {data:'actions', name:''},
                    {data: 'download', name: ''},
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