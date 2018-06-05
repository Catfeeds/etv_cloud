define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'resources/colresource/index',
                    add_url: 'resources/colresource/add',
                    edit_url: 'resources/colresource/edit',
                    del_url: 'resources/colresource/del',
                    multi_url: 'resources/colresource/multi',
                    table: 'col_resource',
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
                        {field: 'title', title: __('Title')},
                        {field: 'describe', title:__('Describe'), operate:false},
                        {field: 'resource_type', title:__('Resource_type'), formatter: Controller.api.formatter.resource_type},
                        {field: 'resource_type', title:__('Preview'), operate:false,
                            formatter: Controller.api.formatter.thumb},
                        {field: 'resource', title: __('Resource'), operate:false,
                            formatter: Controller.api.formatter.url},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'audit_status', title: __('Audit_status'),
                            formatter: Controller.api.formatter.audit_status,
                            searchList: {"0":__('No audit'),"1":__('No egis'), "2":__('Egis'), "3":__('No publish'), "4":__('Publish')}
                        },
                    ]
                ],
                showToggle: false,
                showColumns: false,
                showExport: false,
                commonSearch: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        add: function () {
            Controller.api.bindevent();
            $(".choose_resource").hide();
            $(".resources_type").on("change", function (e) {
                var resource_type = $(this).val();
                $(".choose_resource").hide();
                $("."+"resource_"+resource_type).show();
            });
        },
        edit: function () {
            Controller.api.bindevent();
            var show_type = $(".resources_type").val();
            $(".choose_resource").hide();
            $("."+"resource_"+show_type).show();
            $(".resources_type").on("change", function (e) {
                var resource_type = $(this).val();
                $(".choose_resource").hide();
                $("."+"resource_"+resource_type).show();
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                thumb: function (value, row) {
                    if(value == 'image'){
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                    }else if(value = 'video'){
                        return '<a href="' + row.fullurl + '" target="_blank"><video src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></video></a>';
                    }else{
                        return '';
                    }
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                resource_type: function(value){
                    return '<span class="text-success"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
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
            },
        }
    };
    return Controller;
});