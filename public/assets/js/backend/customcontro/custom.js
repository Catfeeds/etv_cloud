define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customcontro/custom/index',
                    add_url: 'customcontro/custom/add',
                    edit_url: 'customcontro/custom/edit',
                    del_url: 'customcontro/custom/del',
                    multi_url: 'customcontro/custom/multi',
                    table: 'custom',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'custom_id', title: __('Custom_id')},
                        {field: 'custom_name', title: __('Custom_name'), operate:false},
                        {field: 'parent_custom_name', title: __('Parent_custom_name'), operate:false},
                        {field: 'custom_type', title: __('Custom_type'),
                            formatter: Controller.api.formatter.custom_type,
                            searchList: {'hospital': __('Hospital'), 'hotel': __('Hotel')}, style: 'min-width:100px;', operate:false},
                        {field: 'handler', title: __('Handler'), operate:false},
                        {field: 'phone', title: __('Phone'), operate:false},
                        {field: 'detail_address', title: __('Detail_address'), operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:false,
                            formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate, operate:false}
                    ]
                ],
                showToggle: false,
                showExport: false,
                search: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();

            // 地址数据处理
            $("[data-toggle='addresspicker']").data("callback", function(res){
                $("#c-lng").val(res['lng']);
                $("#c-lat").val(res['lat']);
            });
        },
        edit: function () {
            Controller.api.bindevent();

            // 地址数据处理
            $("[data-toggle='addresspicker']").data("callback", function(res){
                $("#c-lng").val(res['lng']);
                $("#c-lat").val(res['lat']);
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                custom_type: function(value, row, index){
                    return '<span class="text-info"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                }
            }
        }
    };
    return Controller;
});