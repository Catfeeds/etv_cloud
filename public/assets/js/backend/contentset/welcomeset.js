define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/welcomeset/index',
                    edit_url: 'contentset/welcomeset/edit',
                    multi_url: 'contentset/welcomeset/multi',
                    dragsort_url: '',
                    table: 'welcome_custom',
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
                        {field: 'id', title: __('Id'), visible:false},
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'title', title: __('Title')},
                        {field: 'resource.filepath', title:__('Preview'), formatter: Controller.api.formatter.thumb},
                        {field: 'resource.filepath', title:__('Filepath'), operate:false,formatter: Controller.api.formatter.url},
                        {field: 'stay_set', title: __('Stay_set'), formatter: Controller.api.formatter.stay_set_text},
                        {field: 'stay_time', title: __('Stay_time'), formatter: Controller.api.formatter.stay_time_text},
                        {field: 'weigh', title: __('Weigh')},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'audit_status', title:__('Audit status'), formatter:Controller.api.formatter.audit_status_text}
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
        edit: function () {
            Controller.api.bindevent();
            // 判断停留设置选项
            if($(".stay_set").val() == 2){
                $(".stay_time_div").show();
            }
            $(".stay_set").on("change", function(){
               if($(".stay_set").val() == 2){
                   $(".stay_time_div").show();
               } else {
                   $(".stay_time_div").hide();
               }
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
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
                stay_set_text: function(value, row){
                    var text = '';
                    switch (value){
                        case 1:
                            text = 'Stay set text1';
                            break;
                        case 2:
                            text = 'Stay set text2';
                            break;
                        default:
                            break;
                    }
                    return '<span class="text-info">' + __(text) + '</span>';
                },
                stay_time_text: function (value, row) {
                    if(value <= 0){
                        return '-';
                    }else{
                        return value;
                    }
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
            },
        }
    };
    return Controller;
});