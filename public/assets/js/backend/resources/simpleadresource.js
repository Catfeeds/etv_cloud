define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'resources/simpleadresource/index',
                    add_url: 'resources/simpleadresource/add',
                    edit_url: 'resources/simpleadresource/edit',
                    del_url: 'resources/simpleadresource/del',
                    multi_url: 'resources/simpleadresource/multi',
                    table: 'simplead_resource',
                }
            });

            var table = $("#table");

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');
            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // 分配资源至客户窗口
                $('.btn-allot').off("click").on("click", function() {
                    var that = this;
                    ////循环弹出多个编辑框
                    $.each(table.bootstrapTable('getSelections'), function (index, row) {
                        var url = 'resources/bindresource/simplead_allot';
                        row = $.extend({}, row ? row : {}, {ids: row[options.pk]});
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
                        {field: 'filepath', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'filepath', title: __('Filepath'), operate:false,formatter: Controller.api.formatter.url},
                        {field: 'size', title: __('Size')+'(MB)', operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'audit_status', title: __('Audit_status'),
                            formatter: Controller.api.formatter.audit_status,
                            searchList: {"0":__('No audit'),"1":__('No egis'), "2":__('Egis'), "3":__('No publish'), "4":__('Publish')}
                        },
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
                thumb: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                audit_status: function(value){
                    var text = '';
                    switch (value){
                        case 0:
                            text = 'No audit';
                            break;
                        case 1:
                            text = 'No egis';
                            break;
                        case 2:
                            text = 'Egis';
                            break;
                        case 3:
                            text = 'No publish';
                            break;
                        case 4:
                            text = 'Publish';
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