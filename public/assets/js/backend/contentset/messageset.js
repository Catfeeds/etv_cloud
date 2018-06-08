define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'contentset/messageset/index',
                    add_url: 'contentset/messageset/add',
                    edit_url: 'contentset/messageset/edit',
                    del_url: 'contentset/messageset/del',
                    multi_url: 'contentset/messageset/multi',
                    table: 'message_notice',
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
                        {field: 'custom.custom_id', title: __('Custom id')},
                        {field: 'custom.custom_name', title: __('Custom name')},
                        {field: 'title', title: __('Title'), operate:false},
                        {field: 'content', title: __('Content'), operate:false},
                        {field: 'push_type', title: __('Push_type'), operate:false, formatter: Controller.api.formatter.push_type_text},
                        {field: 'push_start_time', title: __('Push_start_time'), operate:false},
                        {field: 'push_end_time', title: __('Push_end_time'), operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
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
            action_function: function () {
                $(".push_type").on("change", function(){
                   if('user defined' == $(".push_type option:selected").val()){
                       $(".push_start_time_div").show();
                   }else{
                       $(".push_start_time_div").hide();
                   }
                });
                $(".custom_id").on("change", function(){
                    //销毁已有的节点树
                    $("#treeview").jstree("destroy");
                    Controller.api.rendertree_add();
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
                push_type_text: function (value) {
                    return __(value);
                }
            }
        }
    };
    return Controller;
});