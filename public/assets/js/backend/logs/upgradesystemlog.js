define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/upgradesystemlog/index',
                    add_url: 'logs/upgradesystemlog/add',
                    edit_url: 'logs/upgradesystemlog/edit',
                    del_url: 'logs/upgradesystemlog/del',
                    multi_url: 'logs/upgradesystemlog/multi',
                    table: 'upgrade_system_log',
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
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'mac.mac', title: __('Mac')},
                        {field: 'room', title: __('Room'), operate: false},
                        {field: 'pass_utc', title: __('Pass_utc'), operate: false},
                        {field: 'current_utc', title: __('Current_utc'), operate: false},
                        {field: 'version', title: __('Version'), operate: false},
                        {field: 'message', title: __('Message'), operate: false},
                        {field: 'runtime', title: __('Runtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'login_ip', title: __('Login_ip'), operate: false},
                    ]
                ],
                search:false,
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