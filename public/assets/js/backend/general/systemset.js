define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'general/systemset/index',
                    add_url: 'general/systemset/add',
                    edit_url: 'general/systemset/edit',
                    del_system: 'general/systemset/del',
                    multi_url: 'general/systemset/multi',
                    allot_url: 'general/systemset/allot',
                    table: 'upgrade_system',
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
                        {field: 'utc', title: __('Utc')},
                        {field: 'version', title: __('Version')},
                        {field: 'name', title: __('Name')},
                        {field: 'discription', title: __('Discription')},
                        {field: 'filepath', title: __('Filepath'), formatter:Controller.api.formatter.filepath_url},
                        {field: 'updatetime', title: __('Updatetime')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
                        {field: 'audit_status', title: __('Audit_status'), formatter: Controller.api.formatter.audit_status_text},
                    ]
                ],
                search:false,
                showToggle: false,
                commonSearch: false,
                showExport: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //操作事件之删除
            $(document.body).on("click", '.btn-del-system', function () {
                var ids = Table.api.selectedids(table);
                var options = table.bootstrapTable('getOptions');
                var url = options.extend.del_system;
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
            Controller.api.upload_function();
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.upload_function();
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
                                "url" : "general/systemset/get_tree_list",
                                "data": function(node){
                                    return {"id" : node.id, "row_id":row_id};
                                }
                            }
                        }
                    });
            },
            upload_function: function(){
                require(['upload'], function (Upload) {
                    Upload.api.custom.onDomUploadSuccess = function(response) {
                        //写入信息
                        $("#c-utc").attr("value", response['utc']);
                        $("#c-version").attr("value", response['version']);
                        $("#c-sha1").attr("value", response['sha1']);
                        $("#c-size").attr("value", response['size']);
                    }
                });
            },
            formatter: {
                filepath_url: function (value, row) {
                    return '<a href="' + row.filepath_url + '" target="_blank">'+ value +'</a>';
                },
                audit_status_text: function (value) {
                    return __(value);
                },
            }
        }
    };
    return Controller;
});