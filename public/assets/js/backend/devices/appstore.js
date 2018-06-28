define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/appstore/index',
                    add_url: 'devices/appstore/add',
                    edit_url: 'devices/appstore/edit',
                    del_apk_url: 'devices/appstore/del',
                    multi_url: 'devices/appstore/multi',
                    allot_url: 'devices/appstore/allot',
                    table: 'appstore',
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
                        {field: 'id', title: __('Id'), visible:false, operate:false},
                        {field: 'type', title: __('Type'), operate:false, formatter: Controller.api.formatter.name_text},
                        {field: 'name', title: __('Name'), operate:'LIKE'},
                        {field: 'version', title: __('Version'), operate:false},
                        {field: 'package', title: __('Package'), operate:false},
                        {field: 'remarks', title: __('Remarks'), operate:false},
                        {field: 'filepath', title: __('Filepath'), operate:false, formatter: Controller.api.formatter.filepath_url},
                        {field: 'icon', title: __('Icon'), formatter: Controller.api.formatter.icon_url, operate:false},
                        {field: 'push_all', title: __('Push all'), operate:false, formatter: Controller.api.formatter.push_test},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, operate:false},
                        {field: 'audit_status', title: __('Audit_status'), formatter: Controller.api.formatter.audit_status_text, operate:false},
                    ]
                ],
                search:false,
                showToggle: false,
                showExport: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //操作事件之删除
            $(document.body).on("click", '.btn-del-apk', function () {
                var ids = Table.api.selectedids(table);
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.del_apk_url;
                url = Table.api.replaceurl(url, {ids: ids}, table);
                var options = {url: url};
                Fast.api.ajax(options, function (data, ret) {
                    var success = $(element).data("success") || $.noop;
                    if (typeof success === 'function') {
                        if (false === success.call(element, data, ret)) {
                            return false;
                        }
                    }
                    table.bootstrapTable('refresh');
                }, function (data, ret) {
                    var error = $(element).data("error") || $.noop;
                    if (typeof error === 'function') {
                        if (false === error.call(element, data, ret)) {
                            return false;
                        }
                    }
                });
            });

            //操作事件之批量推送
            $(document.body).on("click", '.btn-allot', function () {
                var ids = Table.api.selectedids(table);
                if(ids.length > 1){
                    Toastr.error(__('Allot one'));
                    return;
                }
                var this_row = Table.api.getrowbyid($("#table"), ids);
                if(this_row.push_all == true){
                    Toastr.error(__('Allot push error'));
                    return;
                }
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.allot_url;
                url = Table.api.replaceurl(url, {ids: ids}, table);
                Fast.api.open(url, __('Allot'));
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        allot: function() {
            var row_id = Config.row_id;
            if(isNaN(row_id)){
                Toastr.error(__('Invalid parameters'));
                return;
            }
            Controller.api.tree_controller(row_id);
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            tree_controller: function(row_id){
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        // 处理选中数据
                        var check_list = $("#treeview").jstree("get_top_checked");
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
                Controller.api.rendertree(row_id);

                //展开
                $(document).on("click", "#expandall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
            },
            rendertree: function (row_id) {
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                            'three_state' : false,
                            'cascade' : 'undetermined+down+up'
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": {
                                "url" : "devices/appstore/get_tree_list",
                                "data": function(node){
                                    return {"id" : node.id, "row_id":row_id};
                                }
                            }
                        }
                    });
            },
            formatter: {
                name_text: function (value) {
                    return __(value);
                },
                filepath_url: function (value, row) {
                    return '<a href="' + row.filepath_url + '" target="_blank">'+ value +'</a>';
                },
                icon_url: function (value, row) {
                    return '<a href="' + row.icon_url + '" target="_blank"><img src="' + row.icon_url + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                audit_status_text: function (value) {
                    return __(value);
                },
                push_test: function (value) {
                    return __(value);
                }
            }
        }
    };
    return Controller;
});