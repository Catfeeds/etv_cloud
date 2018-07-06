define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/adminlog/index',
                    add_url: 'logs/adminlog/add',
                    edit_url: 'logs/adminlog/edit',
                    del_url: 'logs/adminlog/del',
                    multi_url: 'logs/adminlog/multi',
                    table: 'admin_log',
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
                        {field: 'username', title: __('Username')},
                        {field: 'url', title: __('Url'), operate:false},
                        {field: 'title', title: __('Title'), operate:false},
                        {field: 'ip', title: __('Ip'), operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:false, formatter: Table.api.formatter.datetime}
                    ]
                ],
                search:false,
                showToggle:false,
                showColumns:false,
                showExport:false
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