define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'audits/colresource/index',
                    audit_url: 'audits/audit/col_resource_audit',
                    table: 'col_resource',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'title', title: __('Title'), operate:false},
                        {field: 'describe', title:__('Describe'), operate:false},
                        {field: 'resource_type', title:__('Resource_type'), formatter: Controller.api.formatter.resource_type, operate:false},
                        {field: 'resource_type', title:__('Preview'), operate:false,
                            formatter: Controller.api.formatter.thumb},
                        {field: 'resource', title: __('Resource'), operate:false,
                            formatter: Controller.api.formatter.url},
                        {field: 'createtime', title: __('Createtime'), addclass:'datetimerange', formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'updatetime', title: __('Updatetime'),
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'audit_status', title: __('Audit_status'),
                            formatter: Controller.api.formatter.audit_status,
                            searchList: {"unaudited":__('Unaudited'),"no egis":__('No egis'), "egis":__('Egis')}
                        }
                    ]
                ],
                search: false,
                showToggle: false,
                showExport: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 审核指令
            $(document.body).on("click", ".btn-audit", function () {
                var ids = Table.api.selectedids(table);
                element = this;
                var data = element ? $(element).data() : {};
                var url = table.bootstrapTable('getOptions').extend.audit_url;
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                thumb: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                audit_status: function(value){
                    var text = '';
                    switch (value){
                        case 'unaudited':
                            text = 'Unaudited';
                            break;
                        case 'no egis':
                            text = 'No egis';
                            break;
                        case 'egis':
                            text = 'Egis';
                            break;
                        default:
                            text = 'Undefined state';
                    }

                    return '<span class="text-info"><i class="fa fa-circle"></i> ' + __(text) + '</span>';
                }
            }
        }
    };
    return Controller;
});