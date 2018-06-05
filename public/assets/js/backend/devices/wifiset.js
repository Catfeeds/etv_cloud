define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'devices/wifiset/index',
                    add_url: 'devices/wifiset/add',
                    edit_url: 'devices/wifiset/edit',
                    del_url: '',
                    multi_url: 'devices/wifiset/multi',
                    batch_set_url: 'devices/wifiset/batch_set',
                    table: 'device_wifiset',
                }
            });

            var table = $("#table");

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                // 批量设置窗口
                $('.btn-batch-set').off("click").on("click", function() {
                    var table_options = table.bootstrapTable('getOptions');
                    var url = table_options.extend.batch_set_url;
                    Fast.api.open(url, __('Batch set'));
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
                        {field: 'custom.custom_id', title: __('Custom_id')},
                        {field: 'custom.custom_name', title: __('Custom_name'), operate: 'LIKE %...%'},
                        {field: 'wifiset.wifi_ssid', title: __('Wifi_ssid'), operate:false},
                        {field: 'wifiset.wifi_passwd', title: __('Wifi_passwd'), operate:false},
                        {field: 'wifiset.wifi_psk_type', title: __('Wifi_psk_type'), operate:false, formatter:Controller.api.formatter.spk_text},
                        {field: 'wifiset.wifi_hot_spot', title: __('Wifi_hot_spot'), operate:false, formatter:Controller.api.formatter.hot_spot_text},
                        {field: 'wifiset.status', title: __('Status'), formatter: Controller.api.formatter.status, operate:false},
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
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
            $("input[name='row[wifi_psk_type]']").click(function(){
                var wifi_psk_type = $(this).val();
                if(wifi_psk_type == 'none'){
                    $(".wifi_passwd_div").hide();
                }else{
                    $(".wifi_passwd_div").show();
                }
            });
        },
        batch_set: function () {
            Controller.api.tree_controller();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                status: function (value) {
                    if(value != null){
                        //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                        var colorArr = {normal: 'success', hidden: 'grey'};
                        var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                        value = value.charAt(0).toUpperCase() + value.slice(1);
                        //渲染状态
                        var html = '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                        return html;
                    }else{
                        return '-';
                    }

                },
                hot_spot_text: function (value) {
                    if(value != null){
                        return __(value);
                    }else{
                        return '-';
                    }
                },
                spk_text: function (value) {
                    if(value != null){
                        return __(value);
                    }else{
                        return '-';
                    }
                }
            },
            tree_controller: function(){
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if ($("#treeview").size() > 0) {
                        var mac_id = $("#treeview").jstree("get_bottom_checked");
                        $(mac_id).each(function(i){
                            if(isNaN(this)){
                                mac_id.splice(i,1);
                            }
                        });
                        $("input[name='row[mac_ids]']").val(mac_id.join(','));
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
                            //'cascade' : 'undetermined'
                            'cascade' : 'down'
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