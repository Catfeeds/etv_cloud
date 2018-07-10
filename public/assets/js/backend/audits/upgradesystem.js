define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'audits/upgradesystem/index',
                    audit_url: 'audits/audit/upgradesystem_audit',
                    table: 'upgrade_system',
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
                        {field: 'id', title: __('Id'), visible:false},
                        {field: 'utc', title: __('Utc')},
                        {field: 'version', title: __('Version')},
                        {field: 'name', title: __('Name')},
                        {field: 'discription', title: __('Discription')},
                        {field: 'filepath', title: __('Filepath'), formatter:Controller.api.formatter.filepath_url},
                        {field: 'updatetime', title: __('Updatetime')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'audit_status', title: __('Audit_status'), formatter: Controller.api.formatter.audit_status_text},
                    ]
                ],
                search:false,
                showToggle: false,
                commonSearch: false,
                showExport: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 审核指令
            $(document.body).on("click", ".btn-audit", function () {
                var ids = Table.api.selectedids(table);
                element = this;
                var data = element ? $(element).data() : {};
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.audit_url;
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
                filepath_url: function (value, row) {
                    return '<a href="' + row.filepath_url + '" target="_blank">'+ value +'</a>';
                },
                audit_status_text: function (value) {
                    return __(value);
                },
            }
        }
    };
    return Controller;
});