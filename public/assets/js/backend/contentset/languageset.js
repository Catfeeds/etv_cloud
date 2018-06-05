define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/languageset/index',
                    add_url: 'contentset/languageset/add',
                    edit_url: 'contentset/languageset/edit',
                    del_url: 'contentset/languageset/del',
                    multi_url: 'contentset/languageset/multi',
                    table: 'language_setting',
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
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'title', title: __('Title')},
                        {field: 'language', title: __('Language'), formatter: Controller.api.formatter.language_type},
                        {field: 'appellation', title: __('Appellation')},
                        {field: 'wel_words', title: __('Wel_words')},
                        {field: 'signature', title: __('Signature')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                    ]
                ],
                showToggle: false,
                commonSearch: false,
                showExport: false,
                showColumns: false
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
            formatter:{
                language_type: function (value, row, index) {
                    return '<span class="text-success"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                },
            }
        }
    };
    return Controller;
});