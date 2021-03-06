define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'resources/timeappresource/index',
                    add_url: 'resources/timeappresource/add',
                    edit_url: 'resources/timeappresource/edit',
                    del_url: 'resources/timeappresource/del',
                    multi_url: 'resources/timeappresource/multi',
                    table: 'timing_app_resource',
                }
            });

            var table = $("#table");

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // 分配资源至客户窗口
                $('.btn-allot').off("click").on("click", function() {
                    var that = this;
                    ////循环弹出多个编辑框
                    $.each(table.bootstrapTable('getSelections'), function (index, row) {
                        var url = 'resources/bindresource/time_app_allot';
                        row = $.extend({}, row ? row : {}, {ids: row['id']});
                        var url = Table.api.replaceurl(url, row, table);
                        Fast.api.open(url, __('Allot'));
                    });
                });
            });
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'classname', title: __('Classname'), operate:false},
                        {field: 'packagename', title: __('Packagename'), operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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