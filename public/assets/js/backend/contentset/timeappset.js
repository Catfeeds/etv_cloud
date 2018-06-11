define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/timeappset/index',
                    add_url: 'contentset/timeappset/add',
                    edit_url: 'contentset/timeappset/edit',
                    del_url: 'contentset/timeappset/del',
                    multi_url: 'contentset/timeappset/multi',
                    table: 'timing_app_setting',
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
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name')},
                        {field: 'title', title: __('Title'), operate:false},
                        {field: 'data_params', title: __('Data_params'), operate:false},
                        {field: 'repeat_set', title: __('Repeat_set'), formatter:Controller.api.formatter.week_text, operate:false},
                        {field: 'no_repeat_date', title: __('No_repeat_date'), operate:false, formatter:Controller.api.formatter.no_repeat_date},
                        {field: 'start_time', title: __('Start_time'), operate:false},
                        {field: 'end_time', title: __('End_time'), operate:false},
                        {field: 'out_to', title: __('Out_to'), operate:false, formatter:Controller.api.formatter.out_to_text},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
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
            Controller.api.tree_controller();
            Controller.api.action_function();
            Controller.api.rendertree_add();
        },
        edit: function () {
            Controller.api.tree_controller();
            Controller.api.action_function();
            Controller.api.rendertree_edit(nodeData);
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            action_function: function() {
                //客户改变
                $(".custom_id").on("change", function(){
                    //销毁已有的节点树
                    $("#treeview").jstree("destroy");
                    Controller.api.rendertree_add();
                });
                // 重复设置
                $("input[name='row[repeat_set]']").click(function(){
                    var repeat_set_value = $(this).val();
                    if(repeat_set_value == 'no-repeat'){
                        $(".no_repeat_data_div").show();
                        $(".weekday_div").hide();
                    }else if(repeat_set_value == 'everyday'){
                        $(".no_repeat_data_div").hide();
                        $(".weekday_div").hide();
                    }else if(repeat_set_value == 'm-f'){
                        $(".no_repeat_data_div").hide();
                        $(".weekday_div").hide();
                    }else if(repeat_set_value == 'user-defined'){
                        $(".no_repeat_data_div").hide();
                        $(".weekday_div").show();
                    }
                });

            },
            tree_controller: function(){
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        // 处理选中数据
                        var mac_id = $("#treeview").jstree("get_checked");
                        $("input[name='row[mac_ids]']").val(mac_id.join(','));
                    }
                    return true;
                });
                //全选
                $(document).on("click", "#checkall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
            },
            rendertree_add: function () {
                custom_id = $(".custom_id option:selected").val();
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                            'three_state' : false,
                            'cascade' : 'down'
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": {
                                "url" : "contentset/messageset/get_device_list_by_custom/custom_id/" + custom_id,
                                "dataType" : "json"
                            }
                        }
                    });
            },
            rendertree_edit: function (content) {
                custom_id = $(".custom_id option:selected").val();
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                            'three_state' : false,
                            'cascade' : 'down'
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content
                        }
                    });
            },
            formatter:{
                week_text: function (value, row, index) {
                    if(value == 'm-f'){
                        return __('Mon through Fri');
                    }else if(value == 'everyday'){
                        return __('Everyday');
                    }else if(value == 'user-defined'){
                        var weekday = row['weekday'];
                        reg = new RegExp(",", "g");
                        return '周'+weekday.replace(reg, ',周');
                    }else if(value == 'no-repeat'){
                        return __('No-repeat');
                    }else{
                        return '-';
                    }
                },
                no_repeat_date: function (value, row, index) {
                    if('no-repeat' != row['repeat_set']){
                        return '-';
                    }else{
                        return value;
                    }
                },
                out_to_text: function (value) {
                    return __(value);
                }
            }
        }
    };
    return Controller;
});