@extends('frontend.layouts.app')

@section('title', app_name() . ' | 需求审核')

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
	<div class="panel panel-default">
	    <div class="panel-heading">需求审核任务列表</div>
        <div id="alert-editor" class="alert-editor" hidden="">
            <div id="editor" class="editor"></div>
            <div id="save-content" class="btn btn-info pull-right save-content">提交修改意见</div>
        </div>

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
				                            <th>模型风格</th>
				                            <th>模型制作周期</th>
				                            <th>是否审核通过</th>
				                            <th>下载资料包</th>
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
            autoWidth: true
            });
            ue.ready(function() {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });



            $('#products-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("frontend.mlm.demandside.product.list") }}',
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
            });


            $("#products-table").on('click', '.nopass', function(){
            var proid = $(this).attr('data-proid');
            console.log(proid);
            $("#alert-editor").show();
            


        });

        $("#save-content").on('click', function(){
            var content= ue.getContent(),
            // content = content.replace(new RegExp("<","g"),"<").replace(new RegExp(">","g"),">").replace(new RegExp("\"","g"),"'");

            // console.log(content);
            // return;

            $.ajax({
                url: "/auditor/product/nopass",
                type:'POST',
                data:{
                    'productid':proid,
                    'content':content
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
        });

        $("#products-table").on('click', '.pass', function(){
            var proid = $(this).attr('data-proid');
            console.log(proid);
            $.ajax({
                url: "/auditor/product/pass",
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
        });




    });

    	



    
                    
    function doprint(){
    //  bdhtml=window.document.body.innerHTML;   
        // sprnstr="<!--startprint-->";   
        // eprnstr="<!--endprint-->";   
        // prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);   
        // prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));   
        // window.document.body.innerHTML=;  
        // window.print();
    }
</script>
                    
@endsection