define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/devicevisitlog/index',
                    table: 'device_visit_log',
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
                        {field: 'id', title: __('Id')},
                        {field: 'mac', title: __('Mac')},
                        {field: 'message', title: __('Message'), operate:false},
                        {field: 'post_time', title: __('Post_time'), operate:false, formatter: Table.api.formatter.datetime}
                    ]
                ],
                search:false,
                showToggle:false,
                showColumns:false,
                showExport:false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //删除
            $(".toolbar").on('click', '.btn-delete',function () {
                var arr = new Array();
                $.each(table.bootstrapTable('getSelections'), function (index, row) {
                    arr.push(row['id'] + '_' + row['mac_id']);
                });
                $.ajax({
                    url: 'logs/devicevisitlog/delete',
                    type: 'POST',
                    dataType: 'json',
                    data: {'params': arr},
                })
                .done(function(data) {
                    if(data == 0){
                        Toastr.error(__('No rows were deleted'));
                    }else if(data == 1){
                        Toastr.success(__('Operation completed'));
                    }else if(data == -1){
                        Toastr.error(__('Operation failed'));
                    }
                })
                .fail(function() {
                    Toastr.error(__('Operation failed'));
                })
                .always(function() {
                    $('.btn-refresh').trigger('click');
                });
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});