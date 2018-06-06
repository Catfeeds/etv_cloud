define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/sleepset/index',
                    add_url: 'devices/sleepset/add',
                    edit_url: 'devices/sleepset/edit',
                    del_url: 'devices/sleepset/del',
                    multi_url: 'devices/sleepset/multi',
                    table: 'device_sleep',
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
                        {field: 'zxt_custom.custom_id', title: __('Custom id')},
                        {field: 'custom_name', title: __('Custom name')},
                        {field: 'sleep_time', title: __('Sleep_time_start'), formatter:Controller.api.formatter.sleep_time, operate:false},
                        {field: 'sleep_marked_word', title: __('Marked word'), operate:false},
                        {field: 'sleep_countdown_time', title: __('Countdown time'), operate:false},
                        {field: 'sleep_image', title: __('Sleep_image'), formatter: Controller.api.formatter.sleep_image},
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
            },
            formatter: {
                sleep_time: function (value, row) {
                    return row.sleep_time_start + ' 至 '+ row.sleep_time_end;
                },
                sleep_image: function (value, row) {
                    var url = row['url_prefix'] + '/uploads/sleep_image/' + value + '.jpg';
                    return '<a href="' + url + '" target="_blank"><img src="' + url + '"  style="max-height:90px;max-width:120px"></a>';
                }
            }
        }
    };
    return Controller;
});