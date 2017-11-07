@extends('frontend.layouts.app')

@section('title', app_name() . ' | 需求方主页')

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">产品列表 <a href="{{route('frontend.user.demandside.product.create')}}" class="btn pull-right">新建产品</a><div class="btn pull-right">批量提交审核</div> </div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">
	        		
	        	</div>	        	
	        </div>
	    </div>
	</div>
@endsection