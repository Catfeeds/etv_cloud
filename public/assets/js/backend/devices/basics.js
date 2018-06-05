define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/basics/index',
                    add_url: 'devices/basics/add',
                    edit_url: 'devices/basics/edit',
                    del_url: 'devices/basics/del',
                    multi_url: 'devices/basics/multi',
                    order_url: 'devices/basics/order',
                    table: 'device_basics',
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
                        {field: 'id', title: __('Id'), visible:false, operate: false},
                        {field: 'mac', title: __('Mac')},
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name'), operate: 'LIKE %...%'},
                        {field: 'room', title: __('Room'), operate: false},
                        {field: 'room_remark', title: __('Room_remark'), operate: false},
                        {field: 'model', title: __('Model'), operate: false},
                        {field: 'firmware_version', title: __('Firmware_version'), operate: false},
                        {field: 'lately_order', title: __('Lately_order'), formatter:Controller.api.formatter.order_text, operate: false},
                        {field: 'lately_order_result', title: __('Lately_order_result'), formatter:Controller.api.formatter.order_result_text, operate: false},
                        {field: 'last_visit_time', title: __('Last_visit_time'), formatter: Table.api.formatter.datetime, operate: false},
                        {field: 'last_visit_time', title: __('Online'), formatter: Controller.api.formatter.online, operate: false},
                        {field: 'usage', title: __('Usage'), formatter: Controller.api.formatter.usage_text, operate: false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate: false},
                        {field: 'id', title: __('Operate'), table: table, buttons: [
                            {name: 'directive', text: __('directive'), icon: 'fa fa-flash', classname: 'btn btn-xs btn-info btn-dialog', url: 'devices/basics/directive'},
                            {name: 'detail', text: __('detail'),  icon: 'fa fa-list', classname: 'btn btn-xs btn-primary btn-dialog', url: 'devices/basics/detail'}
                        ], operate:false, formatter: Table.api.formatter.buttons}
                    ]
                ],
                search:false,
                showToggle: false,
                showExport: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 指令操作
            $(document.body).on("click", ".btn-order", function () {
                var ids = Table.api.selectedids(table);
                element = this;
                var data = element ? $(element).data() : {};
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.order_url;
                url = Table.api.replaceurl(url, {ids: ids}, table);
                var params = typeof data.params !== "undefined" ? (typeof data.params == 'object' ? $.param(data.params) : data.params) : '';
                var options = {url: url, data: {ids: ids, params: params}};
                Fast.api.ajax(options, function (data, ret) {
                    var success = $(element).data("success") || $.noop;
                    if (typeof success === 'function') {
                        if (false === success.call(element, data, ret)) {
                            return false;
                        }
                    }
                    table.bootstrapTable('refresh');
                }, function (data, ret) {
                    var error = $(element).data("error") || $.noop;
                    if (typeof error === 'function') {
                        if (false === error.call(element, data, ret)) {
                            return false;
                        }
                    }
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
            },
            formatter: {
                order_text: function (value) {
                    return '<span class="pull-right-container"> <span class="label pull-right bg-purple">' +
                     __(value) + '</span></span>';
                },
                order_result_text: function (value) {
                    var html = '';
                    switch (value) {
                        case 'success':
                            html = '<span class="text-success">' + __(value) + '</span>';
                            break;
                        case 'error':
                            html = '<span class="text-danger">' + __(value) + '</span>';
                            break;
                        case 'pending':
                            html = '<span class="text-info">' + __(value) + '</span>';
                            break;
                    }
                    return html;
                },
                usage_text: function (value) {
                    return __(value);
                },
                online: function (value) {
                    var timestamp= Math.round(new Date().getTime()/1000).toString();
                    if(value == "" || timestamp-value > 60*6){
                        return '<span class="pull-right-container"><span class="label pull-right bg-red"> ' +
                            __('Offline')+'</span></span>';
                    }else{
                        return '<span class="pull-right-container"><span class="label pull-right bg-green"> ' +
                            __('Online')+'</span></span>';
                    }
                }
            }
        }
    };
    return Controller;
});