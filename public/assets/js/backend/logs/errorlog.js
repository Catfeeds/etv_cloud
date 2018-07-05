define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/errorlog/index',
                    add_url: 'logs/errorlog/add',
                    edit_url: 'logs/errorlog/edit',
                    del_url: 'logs/errorlog/del',
                    multi_url: 'logs/errorlog/multi',
                    table: 'error_log',
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
                        {field: 'mac', title: __('Mac')},
                        {field: 'error_type', title: __('Error_type'), operate:false},
                        {field: 'error_time', title: __('Error_time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass:'datetimerange'},
                        {field: 'error_name', title: __('Error_name'), operate:false},
                        {field: 'agent', title: __('Agent'), operate:false},
                        {field: 'mode', title: __('Mode'), operate:false},
                        {field: 'referer', title: __('Referer'), operate:false},
                    ]
                ],
                search: false,
                showToggle: false,
                showExport: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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