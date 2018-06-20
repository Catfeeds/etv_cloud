define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/propagandaset/index',
                    add_url: '',
                    edit_url: 'contentset/propagandaset/edit',
                    del_url: '',
                    multi_url: 'contentset/propagandaset/multi',
                    table: 'propaganda_custom'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'resource.title', title: __('Title')},
                        {field: 'resource.filepath', title:__('Preview'), formatter: Controller.api.formatter.thumb},
                        {field: 'resource.filepath', title:__('Filepath'), operate:false,formatter: Controller.api.formatter.url},
                        {field: 'save_set', title: __('Save_set'), formatter: Controller.api.formatter.save_set_text},
                        {field: 'operate', title: __('Operate'), table: table, formatter: Controller.api.formatter.operate},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'audit_status', title:__('Audit status'), formatter:Controller.api.formatter.audit_status_text}
                    ]
                ],
                showToggle: false,
                commonSearch: false,
                showExport: false,
                showColumns: false
            });

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');

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
                    if(row.resource.file_type == 'video'){
                        return '<a href="' + row.fullurl + '" target="_blank"><video src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }else if(row.resource.file_type == 'image'){
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                operate: function (value, row, index) {
                    var table = this.table;
                    // 操作配置
                    var options = table ? table.bootstrapTable('getOptions') : {};
                    // 默认按钮组
                    var buttons = $.extend([], this.buttons || []);

                    if (options.extend.dragsort_url !== '') {
                        buttons.push({
                            name: 'dragsort',
                            icon: 'fa fa-arrows',
                            title: __('Drag to sort'),
                            classname: 'btn btn-xs btn-primary btn-dragsort'
                        });
                    }
                    return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                },
                audit_status_text: function (value) {
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