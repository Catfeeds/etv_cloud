define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/jumpset/index',
                    add_url: 'contentset/jumpset/add',
                    edit_url: 'contentset/jumpset/edit',
                    del_url: 'contentset/jumpset/del',
                    multi_url: 'contentset/jumpset/multi',
                    table: 'jump_setting',
                }
            });

            var table = $("#table");

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // 资源窗口
                $('.btn-resources').off("click").on("click", function() {
                    var that = this;
                    ////循环弹出多个编辑框
                    $.each(table.bootstrapTable('getSelections'), function (index, row) {
                        var url = 'contentset/jumpset/resources/custom_id/'+row['custom']['id'];
                        Fast.api.open(url, __('Resources'));
                    });
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'play_set', title: __('Play_set'), formatter: Controller.api.formatter.play_set_text},
                        {field: 'save_set', title: __('Save_set'), formatter: Controller.api.formatter.save_set_text},
                        {field: 'createtime', title: __('Createtime'), addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
        resources: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    multi_url: 'contentset/jumpset/multi_resource',
                    table: 'jump_custom',
                }
            });
            var resource_table = $("#resource_table");

            // 初始化表格
            resource_table.bootstrapTable({
                url: 'contentset/jumpset/resources',
                sortName: 'id',
                search: false,
                queryParams: function (params) {
                    params.filter = JSON.stringify({'custom_id': Config.custom_id});
                    return {
                        filter: params.filter
                    };
                },
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'filepath', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'filepath', title: __('Filepath'), operate:false,formatter: Controller.api.formatter.url},
                        {field: 'size', title: __('Size')+'(MB)', operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
                        {field: 'audit_status', title: __('Audit status'), formatter: Controller.api.formatter.audit_status_text, operate:false},
                    ]
                ],
                search:false,
                showToggle: false,
                commonSearch: false,
                showExport: false,
                showColumns: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(resource_table);
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                play_set_text: function (value, row) {
                    var text = '';
                    switch (value){
                        case 1:
                            text = 'Play set text1';
                            break;
                        case 2:
                            text = 'Play set text2';
                            break;
                        case 3:
                            text = 'Play set text3';
                            break;
                        case 4:
                            text = 'Play set text4';
                            break;
                        case 5:
                            text = 'Play set text5';
                            break;
                        default:
                            break;
                    }
                    return '<span class="text-info">' + __(text) + '</span>';
                },
                save_set_text: function (value, row) {
                    var text = '';
                    switch (value) {
                        case 1:
                            text = 'Save_set_text1';
                            break;
                        case 2:
                            text = 'Save_set_text2';
                            break;
                    }
                    return '<span class="text-info">' + __(text) + '</span>';
                },
                thumb: function (value, row) {
                    if(row.file_type == 'video'){
                        return '<a href="' + row.fullurl + '" target="_blank"><video src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }else if(row.file_type == 'image'){
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                audit_status_text: function (value, row) {
                    var text = '';
                    switch (value){
                        case 'no release':
                            text = 'No release';
                            break;
                        case 'release':
                            text = 'Release';
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