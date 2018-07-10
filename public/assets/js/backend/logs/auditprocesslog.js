define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/auditprocesslog/index',
                    add_url: 'logs/auditprocesslog/add',
                    edit_url: 'logs/auditprocesslog/edit',
                    del_url: 'logs/auditprocesslog/del',
                    multi_url: 'logs/auditprocesslog/multi',
                    table: 'audit_process_log',
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
                        {field: 'admin.username', title: __('Username')},
                        {field: 'run_time', title: __('Run_time'), formatter: Table.api.formatter.datetime, operate: false},
                        {field: 'audit_type', title: __('Audit_type'), formatter: Controller.api.formatter.audit_type_text,operate: false},
                        {field: 'audit_module', title: __('Audit_module'), formatter: Controller.api.formatter.audit_module_text,operate: false},
                        {field: 'audit_list_id', title: __('Audit_list_id'), operate: false},
                        {field: 'audit_value', title: __('Audit_value'), operate: false}
                    ]
                ],
                search: false,
                showToggle: false,
                showColumns: false,
                visible: false,
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
            },
            formatter: {
                audit_type_text: function(value) {
                    return __(value);
                },
                audit_module_text: function (value) {
                    return __(value);
                }
            }
        }
    };
    return Controller;
});