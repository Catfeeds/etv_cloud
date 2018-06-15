define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {
    //读取选中的条目
    $.jstree.core.prototype.get_all_checked = function (full) {
        return this.get_selected();
    };

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'customcontro/bindcustom/index',
                    add_url: '',
                    edit_url: '',
                    delete_url: 'customcontro/bindcustom/delete',
                    multi_url: '',
                    bind_url: 'customcontro/bindcustom/bind',
                    table: 'admin_custom_bind',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), visible:false},
                        {field: 'custom_id', title: __('Custom_id')},
                        {field: 'custom_name', title: __('Custom_name')},
                        {field: 'handler', title: __('Handler')},
                        {field: 'phone', title: __('Phone')},
                    ]
                ],
                search:false,
                showToggle: false,
                commonSearch: false,
                showExport: false,
                showColumns: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".admin_select").off("change").on("change", function () {
                    var $admin_id = $(".admin_select option:selected").val();
                    options.queryParams = function (params) {
                        return{
                            filter: JSON.stringify({admin_id: $admin_id}),
                            op: JSON.stringify({category_id: '='}),
                        };
                    };
                    table.bootstrapTable('refresh', {});
                    return false;
                });

                $(".btn-bind").off('click').on("click", function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var $admin_id = $(".admin_select option:selected").val();
                    var table_options = table.bootstrapTable('getOptions');
                    var url = table_options.extend.bind_url + '?admin_id=' +$admin_id;
                    Fast.api.open(url, __('Bind'),$(this).data() || {});
                });

                $(".btn-delete").off('click').on("click", function (e) {
                    element = this;
                    e.stopPropagation();
                    e.preventDefault();
                    var $admin_id = $(".admin_select option:selected").val();
                    var table_options = table.bootstrapTable('getOptions');
                    var selected_ids = Table.api.selectedids(table);
                    var $custom_id = selected_ids.join(',');
                    var url = table_options.extend.delete_url;
                    var params = {'admin_id':$admin_id, 'custom_id':$custom_id};
                    var options = {url: url, data: {params:params}};
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

            });

        },
        bind: function () {
            Controller.api.tree_controller();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            tree_controller: function(){
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        var r = $("#treeview").jstree("get_all_checked");
                        $("input[name='row[custom_id]']").val(r.join(','));
                    }
                    return true;
                });
                //销毁已有的节点树
                $("#treeview").jstree("destroy");
                Controller.api.rendertree(nodeData);
                //全选和展开
                $(document).on("click", "#checkall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                $(document).on("click", "#expandall", function () {
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
            },
            rendertree: function (content) {
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                            'three_state' : false,
                            'cascade' : 'undetermined'
                        },
                        "types": {
                            "root": {
                                "icon": "fa fa-folder-open",
                            },
                            "menu": {
                                "icon": "fa fa-folder-open",
                            },
                            "file": {
                                "icon": "fa fa-file-o",
                            }
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content
                        }
                    });
            }
        }
    };
    return Controller;
});