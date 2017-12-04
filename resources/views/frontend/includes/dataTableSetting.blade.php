<script type="text/javascript">
$.dataTableSetting = {
    dom: 'lfrtip',
    processing: true,
    serverSide: true,
    autoWidth: false,
    // searching: false,
    bFilter: true,
    ajax: {
        // url: '{{ route("frontend.mlm.demandside.products.get") }}',
        type: 'POST',
        headers:{
            "_token":'{{ csrf_token() }}'
        },
        error: function (xhr, err) {
            if (err === 'parsererror'){
                location.reload();
                // console.log(err);
            }
        }
    },
    columns: [
        // {data: 'product_no', name: ''},
        // {data: 'cycle', name: ''},
        // {data: 'status_no', name: ''},
        // {data:'actions', name:''},
        // {data: 'fee', name: ''},
    ],

    columnDefs:[
    {
        //设置第一列不参与搜索
        "targets":[0],
        "searchable":false
    }],
    // order: [[1, "asc"]]

    fnServerParams: function(aoData){
    	aoData._rand = Math.random();
    	// aoData.columns.push(
     //        { "name": "product_no", "value": 455 },
     //        { "name": "cycle", "value": 1 },
     //        { "name": "status_no", "value": 1 },
     //        { "name": "actions", "value": 1 },
     //        { "name": "fee", "value":1 }
     //    );

    	// console.log('fnServerParams', aoData);

    	// //搜索就是设置参数，然后销毁datatable重新再建一个
     //    dataTable.fnDestroy(false);
     //    dataTable = $(".dataTables-example").dataTable($.dataTablesSettings);
     //    //搜索后跳转到第一页
     //    dataTable.fnPageChange(0);

    },

    fnDrawCallback: function(){
    	console.log('fnDrawCallback');
    },

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
};
</script>