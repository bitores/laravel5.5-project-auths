@extends('frontend.layouts.app')

@section('title', app_name() . ' | 审核方主页')

@section('after-styles')
@include('vendor.ueditor.assets')
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">审核方</div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">
					

					<!-- 编辑器容器 -->
					<script id="container" name="content" type="text/plain"></script><!-- 实例化编辑器 -->
					<script type="text/javascript">
						var toolbars = [["fullscreen","source","undo","redo","insertunorderedlist",  
    "insertorderedlist","cleardoc","selectall","searchreplace","preview","justifyleft","justifycenter","justifyright","justifyjustify",  
    "touppercase","tolowercase","indent","removeformat","formatmatch","autotypeset","customstyle","paragraph","rowspacingbottom","rowspacingtop","lineheight","fontsize","charts","print","help"]];

					    var ue = UE.getEditor('container',{    
            toolbars: toolbars,
            autoWidth: true,
       });
					    ue.ready(function() {
					        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
					    });

					//     var content= ue.getContent();  
     // 					content = content.replace(new RegExp("<","g"),"<").replace(new RegExp(">","g"),">").replace(new RegExp("\"","g"),""");
     // 					
     					function doprint(){
     					// 	bdhtml=window.document.body.innerHTML;   
						    // sprnstr="<!--startprint-->";   
						    // eprnstr="<!--endprint-->";   
						    // prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);   
						    // prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));   
						    window.document.body.innerHTML=ue.getContent();  
						    window.print();
     					}
					</script>
					<button onclick="doprint()">打印</button>
	        	</div>
	        </div>
	    </div>
	</div>
@endsection