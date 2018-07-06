define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'resources/column/index',
                    add_url: 'resources/column/add',
                    edit_url: 'resources/column/edit',
                    del_url: 'resources/column/del',
                    multi_url: 'resources/column/multi',
                    table: 'column',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'title', title: __('Title'), align: 'left', formatter: Controller.api.formatter.title},
                        {field: 'filepath', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'language_type', title: __('Language_type'), formatter: Controller.api.formatter.language_type},
                        {field: 'column_type', title: __('Column type'), formatter:Controller.api.formatter.column_type_text, operate:false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'id', title:
                            '<a href="javascript:;" class="btn btn-success btn-xs btn-toggle"><i class="fa fa-chevron-up"></i></a>',
                            operate: false, formatter: Controller.api.formatter.subnode
                        },
                        // {field: 'audit_status', title: __('Audit_status'),
                        //     formatter: Controller.api.formatter.audit_status,
                        //     searchList: {"unaudited":__('Unaudited'),"no egis":__('No egis'), "egis":__('Egis')}
                        // }
                    ]
                ],
                showToggle: false,
                showColumns: false,
                showExport: false,
                pagination: false,
                search: false,
                commonSearch: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                //默认隐藏非一级栏目节点
                $(".btn-node-sub:not('.level_first')").closest("tr").hide();

                //显示隐藏子节点
                $(".btn-node-sub").off("click").on("click", function (e) {
                    var status = $(this).data("shown") ? true : false;
                    $("a.btn[data-pid='" + $(this).data("id") + "']").each(function () {
                        $(this).closest("tr").toggle(!status);
                    });
                    $(this).data("shown", !status);
                    return false;
                });

                // 资源窗口
                $('.btn-resources').off("click").on("click", function() {
                    var that = this;
                    ////循环弹出多个编辑框
                    $.each(table.bootstrapTable('getSelections'), function (index, row) {
                        if(row['level'] != 3){
                            Toastr.error(__('Resources tips'));
                            return;
                        }
                        var url = 'resources/colresource/index';
                        row = $.extend({}, row ? row : {}, {ids: row[options.pk]});
                        var url = Table.api.replaceurl(url, row, table);
                        Fast.api.open(url, __('Resources'), {area:["100%", "100%"]});
                    });
                });

                // 分配资源至客户窗口
                $('.btn-allot').off("click").on("click", function() {
                    var that = this;
                    ////循环弹出多个编辑框
                    $.each(table.bootstrapTable('getSelections'), function (index, row) {
                        if(row['level'] != 1){
                            Toastr.error(__('Allot tips'));
                            return;
                        }
                        var url = 'resources/bindresource/column_allot';
                        row = $.extend({}, row ? row : {}, {ids: row[options.pk]});
                        var url = Table.api.replaceurl(url, row, table);
                        Fast.api.open(url, __('Allot'));
                    });
                });
            });

            //展开隐藏一级
            $(document.body).on("click", ".btn-toggle", function (e) {
                $("a.btn[data-id][data-pid][data-pid!=0].disabled").closest("tr").hide();
                var that = this;
                var show = $("i", that).hasClass("fa-chevron-down");
                $("i", that).toggleClass("fa-chevron-down", !show);
                $("i", that).toggleClass("fa-chevron-up", show);
                $("a.btn[data-id][data-pid][data-pid!=0]").not('.disabled').closest("tr").toggle(show);
                $(".btn-node-sub[data-pid=0]").data("shown", show);
            });
            //展开隐藏全部
            $(document.body).on("click", ".btn-toggle-all", function (e) {
                var that = this;
                var show = $("i", that).hasClass("fa-plus");
                $("i", that).toggleClass("fa-plus", !show);
                $("i", that).toggleClass("fa-minus", show);
                $(".btn-node-sub.disabled").closest("tr").toggle(show);
                $(".btn-node-sub").data("shown", show);
            });

        },
        add: function () {
            $(".pid").on("change", function () {
                var pid = $(".pid option:selected").val();
                if(pid == 0){
                    var append_html = '<option value="resource">'+__("Resource")+'</option><option value="app">'+__("App")+'</option>';
                    $(".column_type").empty();
                    $(".column_type").append(append_html);
                }else{
                    $(".column_type option[value='app']").remove();
                }
            });
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
                title: function (value, row) {
                    return row.level == 1? "<span class='text-muted'>" + value + "</span>" : value;
                },
                thumb: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                subnode: function (value, row) {
                    return '<a href="javascript:;" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs '
                        + (row.level==1 ? 'level_first ' : ' ')
                        + (row.haschild == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                },
                language_type: function (value) {
                    return '<span class="text-success"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                },
                column_type_text: function(value){
                    return __(value);
                },
                audit_status: function(value){
                    var text = '';
                    switch (value){
                        case 'unaudited':
                            text = 'Unaudited';
                            break;
                        case 'no egis':
                            text = 'No egis';
                            break;
                        case 'egis':
                            text = 'Egis';
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