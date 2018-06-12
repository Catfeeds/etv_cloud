define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/appstore/index',
                    add_url: 'devices/appstore/add',
                    edit_url: 'devices/appstore/edit',
                    del_apk_url: 'devices/appstore/del',
                    multi_url: 'devices/appstore/multi',
                    table: 'appstore',
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
                        {field: 'id', title: __('Id'), visible:false, operate:false},
                        {field: 'type', title: __('Type'), operate:false, formatter: Controller.api.formatter.name_text},
                        {field: 'name', title: __('Name'), operate:'LIKE'},
                        {field: 'version', title: __('Version'), operate:false},
                        {field: 'package', title: __('Package'), operate:false},
                        {field: 'remarks', title: __('Remarks'), operate:false},
                        {field: 'filepath', title: __('Filepath'), operate:false, formatter: Controller.api.formatter.filepath_url},
                        {field: 'icon', title: __('Icon'), formatter: Controller.api.formatter.icon_url, operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
                        {field: 'audit_status', title: __('Audit_status'), formatter: Controller.api.formatter.audit_status_text, operate:false},
                    ]
                ],
                search:false,
                showToggle: false,
                showExport: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //操作事件
            $(document.body).on("click", '.btn-del-apk', function () {
                var ids = Table.api.selectedids(table);
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.del_apk_url;
                url = Table.api.replaceurl(url, {ids: ids}, table);
                var options = {url: url};
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
                name_text: function (value) {
                    return __(value);
                },
                filepath_url: function (value, row) {
                    return '<a href="' + row.filepath_url + '" target="_blank">'+ value +'</a>';
                },
                icon_url: function (value, row) {
                    return '<a href="' + row.icon_url + '" target="_blank"><img src="' + row.icon_url + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                audit_status_text: function (value) {
                    return __(value);
                }
            }
        }
    };
    return Controller;
});