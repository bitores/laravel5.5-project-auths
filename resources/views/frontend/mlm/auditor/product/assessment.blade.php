@extends('frontend.layouts.app')

@section('title', app_name() . ' | 审核结果')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">审核结果 <div class="btn pull-right" id="downloadPdf">下载文档</div> </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12" id="pdfContainer">
            </div>
        </div>
    </div>
</div>

@endsection

@section('after-scripts')
@include('includes.partials.html2pdf')
<script type="text/javascript">
(function(){
    //HTML反转义
function HTMLDecode(text) { 
var temp = document.createElement("div"); 
temp.innerHTML = text; 
var output = temp.innerText || temp.textContent; 
temp = null; 
return output; 
}
$("#pdfContainer").html(HTMLDecode("{{$content}}"));
})();
</script>
@endsection