define(['jquery', 'bootstrap', 'backend', 'form', 'table'], function ($, undefined, Backend, Form, Table) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/attachment/index',
                    add_url: 'general/attachment/add',
                    edit_url: 'general/attachment/edit',
                    del_url: 'general/attachment/del',
                    multi_url: 'general/attachment/multi',
                    table: 'attachment'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true, operate:false},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'title', title: __('Title')},
                        {field: 'url', title: __('Preview'), formatter: Controller.api.formatter.thumb, operate:false},
                        {field: 'url', title: __('Url'), formatter: Controller.api.formatter.url, operate:false},
                        {field: 'imagewidth', title: __('Imagewidth'), operate:false},
                        {field: 'imageheight', title: __('Imageheight'), operate:false},
                        {field: 'imagetype', title: __('Imagetype'), operate:false},
                        {field: 'filesize', title: __('Filesize'), operate:false},
                        {field: 'mimetype', title: __('Mimetype'), operate:false},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate:false},
                    ]
                ],
                showToggle:false,
                showExport: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/attachment/select',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'title', title: __('Title'), operate: 'LIKE %...%',},
                        {field: 'url', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'url', title: __('Url'), operate:false, formatter: Controller.api.formatter.url},
                        {field: 'mimetype', title: __('Mimetype'), operate: 'LIKE %...%',
                            process: function (value, arg) {
                                return value.replace(/\*/g, '%');
                            }},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'operate', title: __('Operate'), events: {
                                'click .btn-chooseone': function (e, value, row, index) {
                                    var multiple = Backend.api.query('multiple');
                                    multiple = multiple == 'true' ? true : false;
                                    Fast.api.close({url: row.url, multiple: false, rid: row.id});
                                },
                            }, formatter: function () {
                                return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                            }}
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                var urlArr = new Array();
                var ridArr = new Array();
                $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                    urlArr.push(j.url);
                    ridArr.push(j.id);
                });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({url: urlArr.join(","), multiple: true, rid:ridArr.join(",")});
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
                    if (row.mimetype.indexOf("image") > -1) {
                        var style = row.storage == 'upyun' ? '!/fwfh/120x90' : '';
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + style + '" alt="" style="max-height:90px;max-width:120px"></a>';
                    } else {
                        return '<a href="' + row.fullurl + '" target="_blank">' + __('None') + '</a>';
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
            }
        }

    };
    return Controller;
});