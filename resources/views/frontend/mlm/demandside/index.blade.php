@extends('frontend.layouts.app')

@section('title', app_name() . ' | 需求方主页')

@section('after-styles')
    {{ Html::style("/css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@endsection

@section('content')
	<div class="panel panel-default">
	    <div class="panel-heading">产品列表 <a href="{{route('frontend.mlm.demandside.product.create')}}" class="btn pull-right">新建产品</a><div class="btn pull-right">批量提交审核</div> </div>
	    <div class="panel-body">
	        <div class="row">
	        	<div class="col-md-12">
<!-- 	        		@foreach($products as $product)
	        		<h4><a href="{{route('frontend.mlm.demandside.product.edit', $product->id)}}">{{$product->id}}</a></h4>
	        		@endforeach -->

	        		<div class="box box-success">
	        			<div class="box-body">
				            <div class="table-responsive">
				                <table id="products-table" class="table table-condensed table-hover">
				                    <thead>
				                        <tr>
				                            <!-- <th>模型名称</th>
				                            <th>模型制作周期</th>
				                            <th>任务状态</th>
				                            
				                            <th>模型制作费用（单位/元）</th> -->
				                            <!-- <th>ID</th> -->
				                            <th>模型名称</th>
				                            <th>模型制作周期</th>
				                            <!-- <th>style_id</th>
				                            <th>a_id</th>
				                            <th>b_id</th>
				                            <th>user_id</th>
				                            <th>brand_id</th>
				                            <th>cad_id</th>
				                            <th>file_id</th> -->
				                            <th>任务状态</th>
				                            <!-- <th>model_id</th> -->
				                            <th>是否撤单（接单后不允许撤单）</th>
				                            <th>模型制作费用（单位/元）</th>
				                            <!-- <th>introduction</th> -->

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
            $('#products-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("frontend.mlm.demandside.products.get") }}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    // {data: 'id', name: ''},
                    {data: 'product_no', name: ''},
                    {data: 'cycle', name: ''},
                    // {data: 'style_id', name: ''},
                    // {data: 'a_id', name: ''},
                    // {data: 'b_id', name: ''},
                    // {data: 'user_id', name: ''},
                    // {data: 'brand_id', name: ''},
                    // {data: 'cad_id', name: ''},
                    // {data: 'file_id', name: ''},
                    {data: 'status_no', name: ''},
                    // {data: 'model_id', name: ''},
                    {data:'actions', name:''},
                    {data: 'fee', name: ''},
                    // {data: 'introduction', name: ''}
                ],
                // order: [[1, "asc"]]
            });
        });
    </script>
@endsection