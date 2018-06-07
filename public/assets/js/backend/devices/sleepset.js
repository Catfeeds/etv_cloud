define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/sleepset/index',
                    add_url: 'devices/sleepset/add',
                    edit_url: 'devices/sleepset/edit',
                    del_url: 'devices/sleepset/del',
                    multi_url: 'devices/sleepset/multi',
                    multi_edit_url: 'devices/sleepset/multi_edit',
                    table: 'device_sleep',
                }
            });

            var table = $("#table");

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // 批量设置窗口
                $('.btn-multi-edit').off("click").on("click", function() {
                    var table_options = table.bootstrapTable('getOptions');
                    var url = table_options.extend.multi_edit_url;
                    Fast.api.open(url, __('Batch edit'));
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
                        {field: 'mac', title: __('Mac')},
                        {field: 'custom_id', title: __('Custom id')},
                        {field: 'custom_name', title: __('Custom name')},
                        {field: 'sleep_time', title: __('Sleep time'), formatter:Controller.api.formatter.sleep_time, operate:false},
                        {field: 'sleep_marked_word', title: __('Marked word'), operate:false},
                        {field: 'sleep_countdown_time', title: __('Countdown time'), operate:false},
                        {field: 'sleep_image', title: __('Sleep_image'), formatter: Controller.api.formatter.sleep_image, operate:false},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.action_function();
            Controller.api.tree_controller();
        },
        edit: function () {
            Controller.api.action_function();
            Controller.api.bindevent();
        },
        multi_edit: function(){
            Controller.api.action_function();
            Controller.api.tree_controller();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            action_function: function () {
                // 改变预览图
                $(".sleep_image").on("change", function(){
                    var image_url = url_prefix + $(".sleep_image").val() + '.jpg';
                    $(".img-responsive").attr("src", image_url);
                    $(".thumbnail").attr("href", image_url);
                });
            },
            formatter: {
                sleep_time: function (value, row) {
                    return row.sleep_time_start + ' 至 '+ row.sleep_time_end;
                },
                sleep_image: function (value, row) {
                    var url = row['url_prefix'] + '/uploads/sleep_image/' + value + '.jpg';
                    return '<a href="' + url + '" target="_blank"><img src="' + url + '"  style="max-height:90px;max-width:120px"></a>';
                }
            },
            tree_controller: function(){
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        // 处理选中数据
                        var check_list = $("#treeview").jstree("get_checked");
                        var mac_id = new Array();
                        var custom_id = new Array();
                        $(check_list).each(function(i){
                            if(isNaN(this)){
                                custom_id.push(this);
                            }else{
                                mac_id.push(this);
                            }
                        });
                        $("input[name='row[mac_ids]']").val(mac_id.join(','));
                        $("input[name='row[custom_id]']").val(custom_id.join(','));
                    }
                    return true;
                });
                //销毁已有的节点树
                $("#treeview").jstree("destroy");
                Controller.api.rendertree();
                //全选和展开
                $(document).on("click", "#checkall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                $(document).on("click", "#expandall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
            },
            rendertree: function () {
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
                                "url" : "devices/common/get_tree_list",
                                "data": function(node){
                                    return {"id" : node.id};
                                }
                            }
                        }
                    });
            }
        }
    };
    return Controller;
});