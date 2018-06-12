define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/skinset/index',
                    add_url: 'general/skinset/add',
                    edit_url: 'general/skinset/edit',
                    del_url: 'general/skinset/del',
                    multi_url: 'general/skinset/multi',
                    table: 'skin',
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
                        {field: 'title', title: __('Title'), operate:false},
                        {field: 'apk_filepath', title: __('Apk_filepath'), operate:false, formatter: Controller.api.formatter.apk_url},
                        {field: 'web_sign', title: __('Web_sign'), operate:false},
                        {field: 'image_filepath', title: __('Image_filepath'), operate:false, formatter: Controller.api.formatter.thumb},
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
                thumb: function (value, row, index) {
                    return '<a href="' + row.image_url + '" target="_blank"><img src="' + row.image_url + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                apk_url: function (value, row) {
                    return '<a href="' + row.apk_url + '" target="_blank">'+ value +'</a>';
                }
            }
        }
    };
    return Controller;
});