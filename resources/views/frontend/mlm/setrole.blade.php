@extends('frontend.layouts.app')

@section('title', app_name() . ' | 设置角色')

@section('after-styles')
@include('vendor.ueditor.assets')
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">设置角色 <span style="color: red">一旦设置无法更改</span></div>
	    <div class="panel-body">
	        <div class="row">
	        	
					{{ Form::open(['route' => 'frontend.user.bind.role', 'class' => 'form-horizontal']) }}
							{{ Form::input('hidden', 'roleid',4) }}

                            {{ Form::submit('我是需求方', ['class' => 'col-md-4 col-md-offset-1 btn', 'style'=>'height:150px']) }}


                	{{ Form::close() }}

	        		{{ Form::open(['route' => 'frontend.user.bind.role', 'class' => 'form-horizontal']) }}

	        				{{ Form::input('hidden', 'roleid',6) }}

                            {{ Form::submit('我是制作方', ['class' => 'col-md-4 col-md-offset-2 btn', 'style'=>'height:150px']) }}


                	{{ Form::close() }}
	        </div>
	    </div>
	</div>
@endsection