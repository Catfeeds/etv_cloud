define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/timeappset/index',
                    add_url: 'contentset/timeappset/add',
                    edit_url: 'contentset/timeappset/edit',
                    del_url: 'contentset/timeappset/del',
                    multi_url: 'contentset/timeappset/multi',
                    table: 'timing_app_setting',
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
                        {field: 'custom_id', title: __('Custom_id')},
                        {field: 'title', title: __('Title')},
                        {field: 'data_params', title: __('Data_params')},
                        {field: 'repeat_set', title: __('Repeat_set')},
                        {field: 'weekday', title: __('Weekday')},
                        {field: 'no_repeat_date', title: __('No_repeat_date'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'start_time', title: __('Start_time')},
                        {field: 'end_time', title: __('End_time')},
                        {field: 'out_to', title: __('Out_to')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
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