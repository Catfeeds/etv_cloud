define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/popupset/index',
                    add_url: 'contentset/popupset/add',
                    edit_url: 'contentset/popupset/edit',
                    del_url: 'contentset/popupset/del',
                    multi_url: 'contentset/popupset/multi',
                    table: 'popup_setting',
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
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'ad_type', title: __('Ad_type'), formatter:Controller.api.formatter.ad_type_text},
                        {field: 'save_set', title: __('Save_set'), formatter:Controller.api.formatter.save_set_text},
                        {field: 'repeat_set', title: __('Weekday'), formatter:Controller.api.formatter.week_text},
                        {field: 'start_time', title: __('Start_time'), formatter:Controller.api.formatter.start_time_text},
                        {field: 'break_set', title: __('Break_set'), formatter:Controller.api.formatter.break_set_text},
                        {field: 'stay_time', title: __('Stay_time')},
                        {field: 'position', title: __('Position'), formatter:Controller.api.formatter.position_text},
                        {field: 'resource', title: __('Resource'), formatter:Controller.api.formatter.resource},
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
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/popupset/select',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                queryParams: function (params) {
                    params.filter = JSON.stringify({'custom_id': Config.custom_id, 'mimetype':Config.mimetype});
                    return {
                        filter: params.filter
                    };
                },
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'resource.id', title: __('Id'), operate:false},
                        {field: 'resource.title', title: __('Title'), operate: false,},
                        {field: 'resource.filepath', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'resource.filepath', title: __('Url'), operate:false, formatter: Controller.api.formatter.url},
                        {field: 'operate', title: __('Operate'), events: {
                            'click .btn-chooseone': function (e, value, row, index) {
                                Fast.api.close({url: row.resource.filepath, rid:row.resource.id, multiple: false});
                            },
                        }, formatter: function () {
                            return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                        }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            Controller.setting();
        },
        edit: function () {
            Controller.api.bindevent();
            Controller.setting();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                //绑定fachoose选择附件事件
                form = $("form[role=form]");
                if ($(".popup_fachoose", form).size() > 0) {
                    $(".popup_fachoose", form).on('click', function () {
                        var that = this;
                        var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                        var custom_id = $(".custom_id").find("option:selected").val();
                            custom_id = custom_id?custom_id:$(this).data("custom_id");
                        var table = $(this).closest('table');

                        var url = "contentset/popupset/select/custom_id/" + custom_id + "/mimetype/" + mimetype;
                        parent.Fast.api.open(url, __('Resources'), {
                            callback: function (data) {
                                var button = $("#" + $(that).attr("id"));
                                var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                $("#" + input_id).val(data.url).trigger("change");
                                $("#" + input_id + "-rid").val(data.rid).trigger("change");
                            }
                        });
                        return false;
                    });
                }
            },
            formatter: {
                thumb: function (value, row, index) {
                    if (row.resource.file_type=='image') {
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                    } else {
                        return '<a href="' + row.fullurl + '" target="_blank">' + __('None') + '</a>';
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                ad_type_text: function(value, row, index){
                    return __(value);
                },
                save_set_text: function (value, row, index) {
                    var text = '';
                    switch (value) {
                        case 1:
                            text = 'Save_set_text1';
                            break;
                        case 2:
                            text = 'Save_set_text2';
                            break;
                        default:
                            text = '';
                    }
                    return '<span class="text-info">' + __(text) + '</span>';
                },
                week_text: function (value, row, index) {
                    var text = '';
                    if(value == 'user-defined'){
                        var weekday = row['weekday'];
                        reg = new RegExp(",", "g");
                        text = '周'+weekday.replace(reg, ',周');
                    }else{
                        text = value;
                    }
                    return __(text);
                },
                start_time_text: function(value, row, index){
                    var text = '';
                    if(row['repeat_set'] == 'no-repeat'){
                        text = row['no_repeat_date'] + ' ' + row['start_time'];
                    }else{
                        text = value;
                    }
                    return text;
                },
                break_set_text: function(value, row ,index){
                    var text = '';
                    switch (value) {
                        case 1:
                            text = 'Break_set_text1';
                            break;
                        case 2:
                            text = 'Break_set_text2';
                            break;
                        default:
                            text = '';
                            break;
                    }
                    return __(text);
                },
                position_text: function(value, row ,index){
                    var text = '';
                    switch (value) {
                        case 1:
                            text = 'Position UL';
                            break;
                        case 2:
                            text = 'Position UR';
                            break;
                        case 3:
                            text = 'Position LL';
                            break;
                        case 4:
                            text = 'Position LR';
                            break;
                    }
                    return __(text);
                },
                word_text: function(value, row, index){
                    return value;
                },
                resource: function (value, row, index) {
                    if(row['ad_type'] == 'word'){
                        return row['words_tips'];
                    }else if(row['ad_type'] == 'video' || row['ad_type'] == 'image'){
                        if(row['fullurl']){
                            return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + row['resource']['title'] + '</a>';
                        }else {
                            return '-'
                        }

                    }else{
                        return '-';
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
            }
        },
        setting: function () {

            // 重复设置
            $("input[name='row[repeat_set]']").click(function(){
                var repeat_set_value = $(this).val();
                if(repeat_set_value == 'no-repeat'){
                    $(".start_time_div").hide();
                    $(".no_repeat_data_div").show();
                    $(".weekday_div").hide();
                }else if(repeat_set_value == 'everyday'){
                    $(".start_time_div").show();
                    $(".no_repeat_data_div").hide();
                    $(".weekday_div").hide();
                }else if(repeat_set_value == 'm-f'){
                    $(".start_time_div").show();
                    $(".no_repeat_data_div").hide();
                    $(".weekday_div").hide();
                }else if(repeat_set_value == 'user-defined'){
                    $(".start_time_div").show();
                    $(".no_repeat_data_div").hide();
                    $(".weekday_div").show();
                }
            });

            //退出设置
            $("input[name='row[break_set]']").click(function(){
                if($(this).val() == 1){ //不可中途退出
                    $(".stay_time_div").hide();
                }else{
                    $(".stay_time_div").show();
                }
            });

            //广告类型
            $(".ad_type").on("change", function(){
                var ad_type_value = $(this).val();
                if(ad_type_value == 'video'){
                    $(".position_div").hide();
                    $(".words_tips_div").hide();
                    $(".video_resource_div").show();
                    $(".image_resource_div").hide();
                }else if(ad_type_value == 'image'){
                    $(".position_div").show();
                    $(".words_tips_div").hide();
                    $(".video_resource_div").hide();
                    $(".image_resource_div").show();
                }else if(ad_type_value == 'word'){
                    $(".position_div").show();
                    $(".words_tips_div").show();
                    $(".video_resource_div").hide();
                    $(".image_resource_div").hide();
                }
            });

        }

    };
    return Controller;
});